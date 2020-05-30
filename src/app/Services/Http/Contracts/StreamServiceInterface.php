<?php

namespace App\Services\Http\Contracts;

interface StreamServiceInterface
{
    public function getRequestStream(string $url);
}
