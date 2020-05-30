<?php

namespace App\Providers;

use App\Models\Repositories\Contracts\ProductFeedRepositoryInterface;
use App\Services\Http\Contracts\JsonStreamParserInterface;
use App\Services\Http\Contracts\StreamServiceInterface;
use App\Services\Http\JsonStreamParser;
use App\Services\Http\StreamService;
use App\Services\Product\Contracts\ProductsCreateServiceInterface;
use App\Services\Product\Contracts\ProductsFeedServiceInterface;
use App\Services\Product\ProductFeedFromApiStreamService;
use App\Services\Product\ProductsCreateService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Container\Container;

class ServiceServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('client', Client::class);

        $this->app->singleton(StreamServiceInterface::class, function (Container $app) {
            return new StreamService(
                $app->make('client'),
            );
        });

        $this->app->singleton(JsonStreamParserInterface::class, JsonStreamParser::class);

        $this->app->singleton(ProductsCreateServiceInterface::class, function (Container $app) {
            return new ProductsCreateService(
                $app->make('productRepository'),
                $app->make('productAttributeRepository'),
                $app->make('productCharacteristicRepository'),
            );
        });

        $this->app->singleton(ProductsFeedServiceInterface::class, function (Container $app) {
            return new ProductFeedFromApiStreamService(
                $app->make(ProductsCreateServiceInterface::class),
                $app->make(StreamServiceInterface::class),
                $app->make(JsonStreamParserInterface::class),
                $app->make('productFeedRepository'),
                $app->make('languageRepository'),
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
