<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation\Contracts;

use Entense\Extractor\Annotation\ValidationStrategy;

interface Validation
{
    public function validate(mixed $value, ValidationStrategy $validationStrategy): void;
}
