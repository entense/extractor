<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use BackedEnum;
use Entense\Extractor\Annotation\Contracts\{Transformation, Validation};
use Floorberg\Base\Share\Abstracts\EnumAttribute;
use ReflectionProperty;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Enum implements Transformation
{
    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        $type = $property->getType()->getName();

        if ((is_a($type, BackedEnum::class, true) || is_a($type, EnumAttribute::class, true)) && !is_null($value)) {
            if ((is_object($value) || is_string($value)) && property_exists($value, 'value')) {
                $value = $value->value;
            }

            return $type::from($value);
        }

        return null;
    }
}
