<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $product_feed_id
 * @property string $sku
 * @property string $api_key
 * @property float $price
 * @property string $created_at
 * @property string $updated_at
 */
class Product extends Model
{
    /** @var string  */
    protected $table = 'products';

    /** @var array  */
    protected $fillable = [
        'product_feed_id',
        'sku',
        'api_key',
        'price',
    ];

    public function productAttributes() : HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productCharacteristics() : HasMany
    {
        return $this->hasMany(ProductCharacteristic::class);
    }

    /**
     * @param int|null $languageId
     * @return Collection|ProductAttribute[]
     */
    public function getProductAttributes(int $languageId = null)
    {
        $filters = $languageId ? ['language_id' => $languageId] :[];

        return $this->productAttributes()->where($languageId ? ['language_id' => $languageId] : [])->get();
    }

    /**
     * @param int|null $languageId
     * @return Collection|ProductAttribute[]
     */
    public function getProductCharacteristics(int $languageId = null) : Collection
    {
        return $this->productCharacteristics()->where($languageId ? ['language_id' => $languageId] : [])->get();
    }
}
