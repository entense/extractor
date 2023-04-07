<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Ignore
{
    //
}
