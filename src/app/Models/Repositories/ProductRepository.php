<?php

namespace App\Models\Repositories;

use App\Models\Product;
use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public function getModelName() : string
    {
       return Product::class;
    }

    /**
     * @inheritDoc
     */
    public function getAll(array $params = [], bool $paginate = false)
    {
        $query = $this->getQuery();

        $query->with(['productAttributes' => function (HasMany $query) use ($params) {
            if (!empty($params['language_id'])) {
                $query->where(['language_id' => $params['language_id']]);
            }
        }]);

        if (!empty($params['product_feed_id'])) {
            $query->where(['product_feed_id' => $params['product_feed_id']]);
        }

        return $paginate ? $query->paginate() : $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getOne(array $params = [])
    {
        $query = $this->getQuery();

        if (!empty($params['id'])) {
            $query->where('id', $params['id']);
        }

        $query->with(['productAttributes' => function (HasMany $query) use ($params) {
            if (!empty($params['language_id'])) {
                $query->where(['language_id' => $params['language_id']]);
            }
        }]);

        $query->with(['productCharacteristics' => function (HasMany $query) use ($params) {
            if (!empty($params['language_id'])) {
                $query->where(['language_id' => $params['language_id']]);
            }
        }]);

        return $query->first();
    }
}
