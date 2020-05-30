<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 */
class Language extends Model
{
    /** @var string  */
    protected $table = 'languages';

    /** @var array  */
    protected $fillable = [
        'code',
    ];

    public $timestamps = false;
}
