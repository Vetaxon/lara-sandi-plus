<?php

namespace App\Providers;

use App\Models\Repositories\LanguageRepository;
use App\Models\Repositories\ProductRepository;
use App\Models\Repositories\ProductAttributeRepository;
use App\Models\Repositories\ProductCharacteristicRepository;
use App\Models\Repositories\ProductFeedRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('productRepository', ProductRepository::class);
        $this->app->singleton('productAttributeRepository', ProductAttributeRepository::class);
        $this->app->singleton('productCharacteristicRepository', ProductCharacteristicRepository::class);
        $this->app->singleton('productFeedRepository', ProductFeedRepository::class);
        $this->app->singleton('languageRepository' , LanguageRepository::class);
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
