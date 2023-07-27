<?php

namespace Entense\Extractor\Adapters;

use Entense\Extractor\Contracts\Adapter;
use Illuminate\Http\Request as HttpRequest;

final class Request implements Adapter
{
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    public function toArray(): array
    {
        return $this->request->toArray();
    }
}
