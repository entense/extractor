<?php

declare(strict_types=1);

namespace Entense\Extractor\Resolvers;

use Entense\Extractor\Contracts\DataTransfer;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Rules\Properties\ReadWritePropertiesExtension;

class PropertiesExtension implements ReadWritePropertiesExtension
{
    public function isAlwaysRead(PropertyReflection $property, string $propertyName): bool
    {
        $declaringClass = $property->getDeclaringClass();

        if ($declaringClass->implementsInterface(DataTransfer::class)) {
            return $property->isReadable();
        }

        return false;
    }

    public function isAlwaysWritten(PropertyReflection $property, string $propertyName): bool
    {
        $declaringClass = $property->getDeclaringClass();

        if ($declaringClass->implementsInterface(DataTransfer::class)) {
            return $property->isWritable();
        }

        return false;
    }

    public function isInitialized(PropertyReflection $property, string $propertyName): bool
    {
        $declaringClass = $property->getDeclaringClass();

        if ($declaringClass->implementsInterface(DataTransfer::class)) {
            return !$declaringClass->hasConstructor();
        }

        return false;
    }
}
