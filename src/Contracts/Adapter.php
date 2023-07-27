<?php

declare(strict_types=1);

namespace Entense\Extractor\Contracts;

interface Adapter
{
    public function toArray(): array;
}
