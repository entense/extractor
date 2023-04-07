<?php

declare(strict_types=1);

namespace Entense\Extractor\Concerns;

use Entense\Extractor\Annotation\Ignore;

trait AttributableData
{
    #[Ignore]
    private array $attributes = [];

    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    final public function addAttribute(string $key, mixed $value): static
    {
        $this->attributes = array_merge($this->attributes, [$key => $value]);

        return $this;
    }

    final public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }
}
