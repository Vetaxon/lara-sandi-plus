<?php

namespace App\Services\Product\Contracts;

use App\Models\Product;

interface ProductsFeedServiceInterface
{
    public function feed() : void;
}
