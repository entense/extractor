<?php

declare(strict_types=1);

namespace Entense\Extractor\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface TransformableData extends JsonSerializable, Jsonable, Arrayable
{
    public function all(): array;

    public function toArray(): array;

    public function filtered(): array;

    public function toJson($options = 0): string;

    public function jsonSerialize(): array;

    public function transform(bool $transformValues = true): array;
}
