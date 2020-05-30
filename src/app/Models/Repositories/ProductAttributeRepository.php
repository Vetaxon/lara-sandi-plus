<?php

namespace App\Models\Repositories;

use App\Models\ProductAttribute;
use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\ProductAttributeRepositoryInterface;

class ProductAttributeRepository extends AbstractRepository implements ProductAttributeRepositoryInterface
{
    public function getModelName() : string
    {
       return ProductAttribute::class;
    }
}
