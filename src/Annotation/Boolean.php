<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Extractor\Annotation\Contracts\Transformation;
use Entense\Extractor\Annotation\Contracts\Validation;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Boolean implements Validation, Transformation
{
    public function __construct(private ?string $message = null)
    {
        //
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (bool($value) === null) {
            $validationStrategy->setFailure(
                strtr(
                    $this->message ?? 'Value {value} of {path} is not a bool',
                    ['{value}' => var_export($value, true)]
                )
            );
        }
    }

    public function transform(mixed $value, ReflectionProperty $property): mixed
    {
        return bool($value) ?? $value;
    }
}
