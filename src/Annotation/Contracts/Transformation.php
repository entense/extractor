<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation\Contracts;

use ReflectionProperty;

interface Transformation
{
    public function transform(mixed $value, ReflectionProperty $property): mixed;
}
