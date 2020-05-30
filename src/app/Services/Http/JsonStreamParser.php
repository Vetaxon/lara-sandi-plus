<?php

namespace App\Services\Http;

use App\Services\Http\Contracts\JsonStreamParserInterface;
use JsonMachine\JsonMachine;

class JsonStreamParser implements JsonStreamParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse($resource) : \Generator
    {
        foreach (JsonMachine::fromStream($resource) as $key => $value) {
            yield $value;
        }
    }
}
