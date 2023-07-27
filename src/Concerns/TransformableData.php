<?php

declare(strict_types=1);

namespace Entense\Extractor\Concerns;

trait TransformableData
{
    abstract public function transform(bool $transformValues = true): array;

    final public function all(): array
    {
        return $this->transform(transformValues: false);
    }

    public function toArray(): array
    {
        return $this->transform();
    }

    final public function filtered(): array
    {
        return array_filter($this->transform(transformValues: false));
    }

    final public function toJson($options = 0): string
    {
        return json_encode($this->transform(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
