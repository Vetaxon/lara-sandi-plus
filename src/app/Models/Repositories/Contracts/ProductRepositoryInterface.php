<?php


namespace App\Models\Repositories\Contracts;

use App\Models\Product;
use App\Models\Repositories\Abstracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @param bool $paginate
     * @return Product[]|Collection
     */
    public function getAll(array $params = [], bool $paginate = false);

    /**
     * @param array $params
     * @return null|Product|Model
     */
    public function getOne(array $params = []);
}
