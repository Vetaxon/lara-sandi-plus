<?php

use App\Services\Common\Contracts\SetCommandInterface;
use App\Services\Product\Contracts\ProductsFeedServiceInterface;
use Illuminate\Container\Container;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('feed-products', function () {
    $this->comment('Feeding start');
    /** @var  ProductsFeedServiceInterface|SetCommandInterface $feedService */
    $feedService = Container::getInstance()->get(ProductsFeedServiceInterface::class);
    $feedService->setCommand($this);
    $feedService->feed();
    $this->comment('Feeding Finished');
})->describe('Display an inspiring quote');
