<?php

namespace App\Models\Repositories;

use App\Models\ProductFeed;
use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\ProductFeedRepositoryInterface;

class ProductFeedRepository extends AbstractRepository implements ProductFeedRepositoryInterface
{
    public const ALLOWED_STATUSES = [
        ProductFeed::STATUS_IN_PROGRESS,
        ProductFeed::STATUS_DONE,
        ProductFeed::STATUS_FAILED,
    ];

    public function getModelName() : string
    {
       return ProductFeed::class;
    }

    public function getOne(array $params = [])
    {
        $query = $this->getQuery();

        if (!empty($params['status']) && in_array($params['status'], self::ALLOWED_STATUSES)) {
            $query->where('status', $params['status']);
        }

        if (isset($params['last']) && true === $params['last']) {
            $query->orderBy('created_at', 'DESC');
        }


        return $query->first();
    }
}
