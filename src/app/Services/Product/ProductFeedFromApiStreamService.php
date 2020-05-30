<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductFeed;
use App\Models\Repositories\Contracts\LanguageRepositoryInterface;
use App\Models\Repositories\Contracts\ProductFeedRepositoryInterface;
use App\Services\Common\Contracts\SetCommandInterface;
use App\Services\Http\Contracts\StreamServiceInterface;
use App\Services\Http\JsonStreamParser;
use App\Services\Product\Contracts\ProductsCreateServiceInterface;
use App\Services\Product\Contracts\ProductsFeedServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Facades\DB;

class ProductFeedFromApiStreamService implements ProductsFeedServiceInterface, SetCommandInterface
{
    protected const URL = 'https://b2b-sandi.com.ua/api/products';

    protected ProductsCreateServiceInterface $productsCreateService;

    protected StreamServiceInterface $streamService;

    protected JsonStreamParser $jsonStreamParser;

    protected ProductFeedRepositoryInterface $productFeedRepository;

    protected ?ClosureCommand $closureCommand;

    protected LanguageRepositoryInterface $languageRepository;

    protected int $ruId;

    protected int $ukId;

    public function __construct(
        ProductsCreateServiceInterface $productsCreateService,
        StreamServiceInterface $streamService,
        JsonStreamParser $jsonStreamParser,
        ProductFeedRepositoryInterface $productFeedRepository,
        LanguageRepositoryInterface $languageRepository
    )
    {
        $this->productsCreateService = $productsCreateService;
        $this->streamService = $streamService;
        $this->jsonStreamParser = $jsonStreamParser;
        $this->productFeedRepository = $productFeedRepository;
        $this->productFeedRepository = $productFeedRepository;
        $this->languageRepository = $languageRepository;

        $this->ruId = $this->languageRepository->getOne(['lang' => 'ru'])->id;
        $this->ukId = $this->languageRepository->getOne(['lang' => 'uk'])->id;
    }

    public function feed() : void
    {
        /** @var ProductFeed $productFeed */
        $productFeed = $this->productFeedRepository->getNew([
            'status' => ProductFeed::STATUS_IN_PROGRESS,
            'report' => 'Feeding has been initialized via API',
            'source' => self::URL,
        ]);

        $this->log($productFeed->report);

        try {
            $stream = $this->streamService->getRequestStream(self::URL);
        } catch (GuzzleException $guzzleException) {
            $productFeed->update([
                'status' => ProductFeed::STATUS_FAILED,
                'report' => 'Failed due to Request'
            ]);
            $this->log($productFeed->report);
            return;
        } catch (\Throwable $throwable) {
            $productFeed->update([
                'status' => ProductFeed::STATUS_FAILED,
                'report' => 'Failed due to Server Error'
            ]);
            $this->log($productFeed->report);
            return;
        }

        try {
            $jsonGenerator = $this->jsonStreamParser->parse($stream);
        } catch (\Throwable $throwable) {
            $productFeed->update([
                'status' => ProductFeed::STATUS_FAILED,
                'report' => 'Failed due to Parse response Error'
            ]);
            $this->log($productFeed->report);
            return;
        }

        $failedSku = [];
        $counter = 0;
        foreach ($jsonGenerator as $key => $item) {
            $item['product_feed_id'] = $productFeed->id;
            DB::beginTransaction();
            try {
                $product = $this->productsCreateService->store($this->parseArrayToFormat($item, $key));
            } catch (\Throwable $throwable) {
                $this->log('Failed storing product sku ' . ($item['sku'] ?? '?'));
                $this->log($throwable->getMessage());
                $failedSku[] = $item['sku'] ?? '?';
                DB::rollBack();
            }
            DB::commit();
            $counter++;
            $this->log('Successful storing product sku ' . ($item['sku'] ?? '?'));
        }

        $productFeed->update([
            'status' => ProductFeed::STATUS_DONE,
            'report' => $counter . ' items have been feed successfully, ' . count($failedSku) . ' - have been failed',
        ]);
        $this->log($productFeed->report);
    }

    public function setCommand(ClosureCommand $closureCommand) : void
    {
        $this->closureCommand = $closureCommand;
    }

    protected function log(string $description = '') : void
    {
        if (!empty($this->closureCommand)) {
            $this->closureCommand->comment($description);
        }
    }

    protected function parseArrayToFormat(array $item, $key) : array
    {
        $newItem = [
            'sku' => $item['sku'] ?? '',
            'product_feed_id' => $item['product_feed_id'] ?? '',
            'price' => $item['price'] ?? 0,
            'api_key' => $key,
            'product_attributes' => [
                [
                    'name' => $item['name_ru'] ?? '',
                    'description' => $item['description_ru'] ?? '',
                    'language_id' => $this->ruId,
                ],
                [
                    'name' => $item['name_uk'] ?? '',
                    'description' => $item['description_uk'] ?? '',
                    'language_id' => $this->ukId,
                ],
            ],
        ];

        $newItem['product_characteristics'] = [];

        foreach ($item['characteristics'] ?? [] as $value) {
            $newItem['product_characteristics'][] = [
                'name' => $value['name_ru'] ?? '',
                'value' => $value['value_ru'] ?? '',
                'language_id' => $this->ruId,
            ];
            $newItem['product_characteristics'][] = [
                'name' => $value['name_uk'] ?? '',
                'value' => $value['value_uk'] ?? '',
                'language_id' => $this->ukId,
            ];
        }

        return $newItem;
    }
}
