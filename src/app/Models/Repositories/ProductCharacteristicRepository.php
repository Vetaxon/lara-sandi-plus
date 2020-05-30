<?php

namespace App\Models\Repositories;

use App\Models\ProductCharacteristic;
use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\ProductCharacteristicRepositoryInterface;

class ProductCharacteristicRepository extends AbstractRepository implements ProductCharacteristicRepositoryInterface
{
    public function getModelName() : string
    {
       return ProductCharacteristic::class;
    }
}
