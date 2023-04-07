<?php

declare(strict_types=1);

namespace Entense\Extractor\Concerns;

use Closure;
use Entense\Extractor\Annotation\Ignore;

trait AppendableData
{
    #[Ignore]
    protected array $additional = [];

    final public function with(): array
    {
        return [];
    }

    final public function additional(array $additional): static
    {
        $this->additional = array_merge($this->additional, $additional);

        return $this;
    }

    final public function getAdditionalData(): array
    {
        $additional = $this->with();

        foreach ($this->additional as $name => $value) {
            $additional[$name] = $value instanceof Closure
                ? ($value)($this)
                : $value;
        }

        return $additional;
    }
}
