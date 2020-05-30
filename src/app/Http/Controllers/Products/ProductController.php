<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\CoreAbstractController;
use App\Models\Language;
use App\Models\ProductFeed;
use App\Models\Repositories\Contracts\LanguageRepositoryInterface;
use App\Models\Repositories\Contracts\ProductFeedRepositoryInterface;
use App\Models\Repositories\Contracts\ProductRepositoryInterface;

/**
 * @property-read ProductFeedRepositoryInterface $productFeedRepository
 * @property-read ProductRepositoryInterface $productRepository
 * @property-read LanguageRepositoryInterface $languageRepository
 */
class ProductController extends CoreAbstractController
{
    protected ?Language $language;

    public function __construct()
    {
        $lang = $this->request->get('lang');
        $lang = !empty($lang) && in_array($lang, $this->config['app']['lang']['allowed']) ? $lang : $this->config['app']['lang']['default'];
        $this->language = $this->languageRepository->getOne(['lang' => $lang]);
    }

    public function index()
    {
        $lastFeedDone = $this->productFeedRepository->getOne([
            'last' => true,
            'status' => ProductFeed::STATUS_DONE,
        ]);

        $lastFeed = $this->productFeedRepository->getOne([
            'last' => true,
        ]);

        if (!empty($lastFeedDone)) {
            $products = $this->productRepository->getAll([
                'product_feed_id' => $lastFeedDone->id,
                'language_id' => $this->language->id,
            ], true)->toArray();
            $products['data'] = array_map(function (array $product) {
                $product['product_attributes'] = array_shift($product['product_attributes']);
                return $product;
            }, $products['data']);
            $products['prev_page_url'] = $products['prev_page_url'] . '&lang=' . $this->language->code;
            $products['next_page_url'] = $products['next_page_url'] . '&lang=' . $this->language->code;
        }

        if (!empty($lastFeed)) {
            $productsLastFeed = $this->productRepository->getAll([
                'product_feed_id' => $lastFeed->id,
            ])->count();
        }

        return $this->getView()->with([
            'lastFeedDone' => $lastFeedDone ? $lastFeedDone->toArray() : [],
            'lastFeedInProgress' => $lastFeed && $lastFeed->status === ProductFeed::STATUS_IN_PROGRESS,
            'lastFeed' => $lastFeed ? $lastFeed->toArray() : [],
            'productsLastFeed' => $productsLastFeed ?? 0,
            'products' => $products ?? [],
        ]);
    }

    public function show(int $productId)
    {
        $product = $this->productRepository->getOne([
            'id' => $productId,
            'language_id' => $this->language->id,
        ]);

        if (empty($product)) {
            return response('Not found', 404);
        }

        $product = $product->toArray();
        $product['product_attributes'] = array_shift($product['product_attributes']);

        return $this->getView()->with([
            'product' => $product,
        ]);
    }

    protected function getView($view = null, $data = [], $mergeData = []) : \Illuminate\View\View
    {
        $view = parent::getView($view, $data, $mergeData);
        $languages = $this->config['app']['lang']['allowed'];
        usort($languages, function (string $a, string $b) {
            return $b === $this->language->code;
        });
        $view->with([
            'languages' => $languages,
        ]);
        return $view;
    }
}
