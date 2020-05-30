<?php

namespace App\Models\Repositories;

use App\Models\Language;
use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\LanguageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepository extends AbstractRepository implements LanguageRepositoryInterface
{
    public function getModelName() : string
    {
       return Language::class;
    }

    public function getOne(array $params = [])
    {
        $query = $this->getQuery();

        if (!empty($params['lang'])) {
            $query->where('code', $params['lang']);
        }

        return $query->first();
    }
}
