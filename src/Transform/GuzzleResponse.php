<?php

namespace Entense\Extractor\Transform;

use Entense\Extractor\Contracts\Transform;
use GuzzleHttp\Psr7\Response;

class GuzzleResponse implements Transform
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    final public function toArray(): array
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }
}
