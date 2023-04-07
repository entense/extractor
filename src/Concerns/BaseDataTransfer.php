<?php

declare(strict_types=1);

namespace Entense\Extractor\Concerns;

use Entense\Extractor\Contracts\TransformableData;
use Entense\Extractor\DataTransferObject;
use Entense\Extractor\Transform\{GuzzleResponse, Request};
use Entense\Extractor\Transformers\DataTransformer;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request as HttpRequest;

trait BaseDataTransfer
{
    final public static function from(mixed $input = []): static
    {
        if ($input instanceof HttpRequest) {
            return self::fromRequest($input);
        }

        if ($input instanceof Response) {
            return self::fromGuzzleResponse($input);
        }

        if ($input instanceof TransformableData) {
            $input = $input->all();
        } elseif ($input instanceof Arrayable) {
            $input = $input->toArray();
        }

        $dto = new DataTransferObject(static::class);

        $object = $dto->from($input)->getInstance();

        return $object;
    }

    final public static function make($input): static
    {
        return self::from($input);
    }

    final public function fresh(array $input = []): static
    {
        $values = [];

        if ($this instanceof TransformableData) {
            $values = array_merge($this->all(), $input);
        }

        return $this->from($values);
    }

    final public static function fromGuzzleResponse(Response $response): static
    {
        return self::from((new GuzzleResponse($response))->toArray());
    }

    final public static function fromRequest(HttpRequest $request): static
    {
        return self::from((new Request($request))->toArray());
    }

    public function boot(): static
    {
        return $this;
    }

    final public function transform(bool $transformValues = true): array
    {
        return DataTransformer::create($transformValues)->transform($this);
    }
}
