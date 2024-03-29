<?php

declare(strict_types=1);

namespace Entense\Extractor\Annotation;

use Attribute;
use Entense\Extractor\Annotation\Contracts\Validation;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NotIn implements Validation
{
    /**
     * @param array<mixed, mixed> $values
     */
    public function __construct(private array $values, private ?string $message = null)
    {
    }

    public function validate(mixed $value, ValidationStrategy $validationStrategy): void
    {
        if (in_array(needle: $value, haystack: $this->values, strict: true)) {
            $validationStrategy->setFailure(
                strtr(
                    $this->message ?? '{value} of {path} must not be in {values}',
                    [
                        '{value}' => var_export($value, true),
                        '{values}' => var_export($this->values, true),
                    ]
                )
            );
        }
    }
}
