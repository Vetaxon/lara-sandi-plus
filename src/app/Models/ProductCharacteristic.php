<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $language_id
 * @property string $name
 * @property string $value
 */
class ProductCharacteristic extends Model
{
    /** @var string  */
    protected $table = 'product_characteristics';

    /** @var array  */
    protected $fillable = [
        'product_id',
        'language_id',
        'name',
        'value',
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
