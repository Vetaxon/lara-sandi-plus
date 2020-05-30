<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Repositories\Contracts\ProductAttributeRepositoryInterface;
use App\Models\Repositories\Contracts\ProductCharacteristicRepositoryInterface;
use App\Models\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Product\Contracts\ProductsCreateServiceInterface;
use Illuminate\Support\Facades\DB;

class ProductsCreateService implements ProductsCreateServiceInterface
{
    protected ProductRepositoryInterface $productRepository;

    protected ProductAttributeRepositoryInterface $productAttributeRepository;

    protected ProductCharacteristicRepositoryInterface $productCharacteristicRepository;

    /**
     * ProductsCreateService constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param ProductCharacteristicRepositoryInterface $productCharacteristicRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        ProductCharacteristicRepositoryInterface $productCharacteristicRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->productCharacteristicRepository = $productCharacteristicRepository;
    }

    public function store(array $product) : Product
    {
        $newProduct = $this->productRepository->getNew($product);

        foreach ($product['product_attributes'] ?? [] as $productAttribute) {
            $newProductAttr = $this->productAttributeRepository->getNew(array_merge($productAttribute, ['product_id' => $newProduct->id]));
        }

        foreach ($product['product_characteristics'] ?? [] as $productChar) {
            $newProductChar = $this->productCharacteristicRepository->getNew(array_merge($productChar, ['product_id' => $newProduct->id]));
        }

        return $newProduct;
    }
}
