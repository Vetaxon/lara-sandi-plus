<?php

namespace App\Services\Http\Contracts;

interface JsonStreamParserInterface
{
    /**
     * @param resource $resource
     * @return \Generator
     */
    public function parse($resource) : \Generator;
}
