<?php

namespace App\Services\Product\Contracts;

use App\Models\Product;

interface ProductsCreateServiceInterface
{
    public function store(array $product) : Product;
}
