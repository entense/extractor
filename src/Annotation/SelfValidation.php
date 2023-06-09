<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;

#[Attribute(flags: Attribute::TARGET_CLASS)]
final class SelfValidation
{
    public function __construct(private string $method)
    {
        //
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
