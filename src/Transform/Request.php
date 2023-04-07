<?php

namespace Entense\Extractor\Transform;

use Entense\Extractor\Contracts\Transform;
use Illuminate\Http\Request as HttpRequest;

class Request implements Transform
{
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    final public function toArray(): array
    {
        return $this->request->toArray();
    }
}
