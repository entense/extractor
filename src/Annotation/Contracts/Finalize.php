<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation\Contracts;

interface Finalize
{
    public function finalize(array $input): void;
}
