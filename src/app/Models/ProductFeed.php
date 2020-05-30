<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *@property int $id
 *@property string $source
 *@property string $status
 *@property string $report
 */
class ProductFeed extends Model
{
    /** @var  string  */
    public const STATUS_IN_PROGRESS = 'in_progress';

    /** @var string  */
    public const STATUS_DONE = 'done';

    /** @var string  */
    public const STATUS_FAILED = 'failed';

    /** @var string  */
    protected $table = 'product_feeds';

    /** @var array  */
    protected $fillable = [
        'source',
        'status',
        'report',
    ];
}
