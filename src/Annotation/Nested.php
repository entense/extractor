<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Extractor\Annotation\Contracts\Transformation;
use ReflectionProperty;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Nested implements Transformation
{
    public function __construct(private string $class, private ?string $message = null)
    {
        //
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        if (is_array($value)) {
            foreach ($value as &$item) {
                $item = $this->class::from($item);
            }

            return $value;
        }
    }
}
