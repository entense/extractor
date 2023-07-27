<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Illuminate\Support\Str;
use ReflectionProperty;

#[Attribute(flags: Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Alias
{
    public function __construct(private string|array $name = '')
    {
        //
    }

    public function getName(ReflectionProperty $property): string|array
    {
        if (!$this->name) {
            $name = $property->getName();

            return [Str::camel($name), Str::snake($name)];
        }

        return $this->name;
    }
}
