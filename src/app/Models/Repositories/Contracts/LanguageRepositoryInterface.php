<?php


namespace App\Models\Repositories\Contracts;

use App\Models\Language;
use App\Models\Repositories\Abstracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface LanguageRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @return null|Language|Model
     */
    public function getOne(array $params = []);
}
