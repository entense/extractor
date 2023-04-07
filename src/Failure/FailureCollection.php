<?php

declare(strict_types=1);

namespace Entense\Extractor\Failure;

class FailureCollection
{
    protected const PATH = '{path}';

    private array $path = [];

    private array $failures = [];

    public function pushPath(string $path): void
    {
        $this->path[] = $path;
    }

    public function popPath(): ?string
    {
        return array_pop($this->path);
    }

    public function setFailure(string $failure): void
    {
        $this->failures[] = strtr($failure, [self::PATH => implode('.', $this->path)]);
    }

    public function hasFailures(): bool
    {
        return $this->failures !== [];
    }

    public function getFailures(): array
    {
        return $this->failures;
    }
}
