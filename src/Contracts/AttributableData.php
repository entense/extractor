<?php

declare(strict_types=1);

namespace Entense\Extractor\Contracts;

interface AttributableData
{
    public function getAttributes(): array;

    public function addAttribute(string $key, mixed $value): static;

    public function setAttributes(array $attributes): static;
}
