<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\CoreAbstractController;
use App\Models\ProductFeed;
use App\Models\Repositories\Contracts\LanguageRepositoryInterface;
use App\Models\Repositories\Contracts\ProductFeedRepositoryInterface;
use App\Models\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Product\Contracts\ProductsFeedServiceInterface;

/**
 * @property-read ProductFeedRepositoryInterface $productFeedRepository
 * @property-read ProductRepositoryInterface $productRepository
 * @property-read LanguageRepositoryInterface $languageRepository
 */
class UploadProductsController extends CoreAbstractController
{
    public function upload()
    {
        dispatch(function () {
            $this->app->get(ProductsFeedServiceInterface::class)->feed();
        })->afterResponse();

        return response()->json([
            'message' => 'Success',
        ]);
    }

    public function checkStatus()
    {
        $lastFeed = $this->productFeedRepository->getOne([
            'last' => true,
            'status' => ProductFeed::STATUS_IN_PROGRESS,
        ]);

        if (!empty($lastFeed)) {
            $products = $this->productRepository->getAll([
                'product_feed_id' => $lastFeed->id,
            ])->count();
        }
        return response()->json([
            'message' => 'Success',
            'data' => array_merge($lastFeed ? $lastFeed->toArray() : [], [
                'uploaded' => $products ?? 0,
            ]),
        ]);
    }

}
