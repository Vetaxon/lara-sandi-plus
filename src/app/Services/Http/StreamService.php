<?php

namespace App\Services\Http;

use App\Services\Http\Contracts\StreamServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class StreamService implements StreamServiceInterface
{
    protected ClientInterface $client;


    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return resource
     * @throws GuzzleException
     */
    public function getRequestStream(string $url)
    {
        $response = $this->client->request('GET', $url, ['stream' => true]);

        return \GuzzleHttp\Psr7\StreamWrapper::getResource($response->getBody());
    }
}
