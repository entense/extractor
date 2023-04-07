<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Type\Defaultable;
use Entense\Type\Type as PhpType;
use InvalidArgumentException;
use ReflectionNamedType;
use ReflectionProperty;
use Entense\Extractor\Contracts\BaseDataTransfer;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Optional
{
    public function __construct(private mixed $value = null)
    {
        //
    }

    public function getValue(ReflectionProperty $property): mixed
    {
        $reflectionNamedType = $property->getType();

        if (!($reflectionNamedType instanceof ReflectionNamedType)) {
            throw new InvalidArgumentException('Cannot cast to unknown type');
        }

        $propertyType = PhpType::fromReflectionType($reflectionNamedType);

        $value = $this->value ?? ($propertyType instanceof Defaultable ? $propertyType->getDefaultValue() : $this->value);

        if ($value instanceof BaseDataTransfer || is_subclass_of($value, BaseDataTransfer::class)) {
            return $value::from();
        }

        return $value;
    }
}
