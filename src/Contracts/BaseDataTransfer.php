<?php

declare(strict_types=1);

namespace Entense\Extractor\Contracts;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request as HttpRequest;

interface BaseDataTransfer
{
    public static function from(mixed $input = []): static;

    public static function make($input): static;

    public function fresh(array $input = []): static;

    public static function fromGuzzleResponse(Response $response): static;

    public static function fromRequest(HttpRequest $request): static;

    public function boot(): static;
}
