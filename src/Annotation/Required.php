<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use InvalidArgumentException;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Required
{
    private Exceptional $exceptional;

    public function __construct(string $reason, string $exception = InvalidArgumentException::class)
    {
        $this->exceptional = new Exceptional(message: $reason, exception: $exception);
    }

    public function execute(): void
    {
        $this->exceptional->execute();
    }
}
