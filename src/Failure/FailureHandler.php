<?php

declare(strict_types=1);

namespace Entense\Extractor\Failure;

use Entense\Extractor\ValidationException;

class FailureHandler
{
    public function handle(FailureCollection $collection): void
    {
        if (!$collection->hasFailures()) {
            return;
        }

        throw new ValidationException(implode(PHP_EOL, $collection->getFailures()));
    }
}
