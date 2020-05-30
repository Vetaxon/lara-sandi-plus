<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $language_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class ProductAttribute extends Model
{
    /** @var string  */
    protected $table = 'product_attributes';

    /** @var array  */
    protected $fillable = [
        'product_id',
        'language_id',
        'name',
        'description',
    ];

    public function language() : BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function products() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
