<?php

declare(strict_types=1);

namespace Entense\Extractor\Contracts;

interface AppendableData
{
    public function with(): array;

    public function additional(array $additional): object;

    public function getAdditionalData(): array;
}
