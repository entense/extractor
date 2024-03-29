<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Extractor\Annotation\Contracts\Validation;

#[Attribute(flags: Attribute::TARGET_PROPERTY)]
final class Min implements Validation
{
    public function __construct(private int|float $minValue, private ?string $message = null)
    {
        //
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (is_int($value) || is_float($value)) {
            if ($value < $this->minValue) {
                $validationStrategy->setFailure($this->message ?? 'Value ' . var_export($value, true) . ' of {path} must be >= ' . $this->minValue);
            }

            return;
        }

        if (is_string($value)) {
            if (strlen($value) < $this->minValue) {
                $validationStrategy->setFailure($this->message ?? 'Value ' . var_export($value, true) . ' of {path} must have at least a length of ' . $this->minValue);
            }

            return;
        }

        if (is_array($value) && count($value) < $this->minValue) {
            $validationStrategy->setFailure($this->message ?? 'Value ' . var_export($value, true) . ' of {path} must have at least a length of ' . $this->minValue);
        }
    }
}
