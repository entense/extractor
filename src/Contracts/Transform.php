<?php

declare(strict_types=1);

namespace Entense\Extractor\Contracts;

interface Transform
{
    public function toArray(): array;
}
