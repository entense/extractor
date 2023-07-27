<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Extractor\Annotation\Contracts\Transformation;
use ReflectionProperty;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class NestedWrap implements Transformation
{
    public function __construct(private string $key = 'data')
    {
        //
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        return [
            $this->key => $value,
        ];
    }
}
