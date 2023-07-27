<?php

namespace Entense\Extractor\Adapters;

use GuzzleHttp\Psr7\Response;
use Entense\Extractor\Contracts\Adapter;

final class GuzzleResponse implements Adapter
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function toArray(): array
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }
}
