<?php


namespace App\Models\Repositories\Contracts;

use App\Models\ProductFeed;
use App\Models\Repositories\Abstracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface ProductFeedRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @return null|ProductFeed|Model
     */
    public function getOne(array $params = []);
}
