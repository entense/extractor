<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use InvalidArgumentException;
use ReflectionClass;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Transform
{
    public function __construct(private ?string $class = null, private ?string $method = null, private bool $allowNull = false)
    {
        if ($this->class !== null) {
            $this->method ??= '__invoke';

            $refl = new ReflectionClass($this->class);

            if (!$refl->hasMethod($this->method)) {
                throw new InvalidArgumentException('Class ' . $this->class . ' needs to implement ' . $this->method);
            }
        }
    }

    public function transform(mixed $value): mixed
    {
        if (is_null($value) && !$this->allowNull) {
            return $value;
        }

        if ($this->method === null) {
            return $value;
        }

        if ($this->class === null) {
            if (!is_callable($this->method)) {
                throw new InvalidArgumentException('Need an callable, not ' . $this->method);
            }

            return ($this->method)($value);
        }

        $refl = new ReflectionClass($this->class);

        if (!$refl->hasMethod($this->method)) {
            throw new InvalidArgumentException('Class ' . $this->class . ' needs to implement ' . $this->method);
        }

        $method = $refl->getMethod($this->method);

        if ($method->isStatic()) {
            return $method->invoke(null, $value);
        }

        return $method->invoke($refl->newInstance(), $value);
    }
}
