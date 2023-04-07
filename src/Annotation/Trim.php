<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Extractor\Annotation\Contracts\Transformation;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Trim implements Transformation
{
    public function __construct(private string $characters = "/(?:\s\s+|\n|\t)/", private string $replace = ' ')
    {
        //
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->transform($item, $property);
            }

            return $value;
        }

        return is_string($value) ? preg_replace($this->characters, $this->replace, $value) : $value;
    }
}
