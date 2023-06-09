<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Name
{
    public function __construct(private string $name)
    {
        //
    }

    public function getName(): string
    {
        return $this->name;
    }
}
