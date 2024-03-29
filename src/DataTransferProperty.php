<?php

declare(strict_types=1);

namespace Entense\Extractor;

use Entense\Extractor\Annotation\{Alias, Ignore, Name, Optional, Path, Reject, Required, ValidationStrategy};
use Entense\Extractor\Contracts\{AttributableData, DataTransfer};
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Throwable;

/**
 * @template T of object
 */
final class DataTransferProperty
{
    private bool $ignore;
    /** @var string[] */
    private array $names = [];

    private DataTransfer $instance;

    private bool $hasDefaultValue;
    private mixed $defaultValue;
    private ValidationStrategy $validationStrategy;

    /**
     * @param DataTransferObject<T> $parent
     *
     * @throws ReflectionException
     */
    public function __construct(private ReflectionProperty $property, DataTransferObject $parent)
    {
        $this->validationStrategy = $parent->getValidationStrategy();

        if (version_compare(PHP_VERSION, '8.1') < 0) {
            $property->setAccessible(true);
        }

        $this->ignore = $this->property->getAttributes(Ignore::class) !== [];
        $this->setNames();
        $this->instance = $parent->getInstance();

        if ($property->hasDefaultValue()) {
            $this->hasDefaultValue = true;
            $this->defaultValue = $property->getDefaultValue();
        } else {
            $parameter = $this->getPromotedConstructorParameter($parent->getConstructor(), $property->getName());
            if ($parameter !== null && $parameter->isOptional()) {
                $this->hasDefaultValue = true;
                $this->defaultValue = $parameter->getDefaultValue();
            } else {
                $this->hasDefaultValue = $property->getType()?->allowsNull() ?? false;
                $this->defaultValue = null;
            }
        }
    }

    public function isIgnored(): bool
    {
        return $this->ignore;
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws Throwable
     */
    public function ignoreIn(array &$input): void
    {
        foreach ($this->names as $name) {
            if (!array_key_exists($name, $input)) {
                continue;
            }

            $this->handleRejected();
            unset($input[$name]);
        }
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws Throwable
     */
    public function setValueFrom(array &$input): void
    {
        foreach ($this->names as $name) {
            $value = $this->handlePath($input);

            if ($value === null && !array_key_exists($name, $input)) {
                continue;
            }

            $this->handleRejected();

            if ($value === null) {
                $value = $input[$name];
                unset($input[$name]);
            }

            $value = new DataTransferValue($value, $this->property, $this->validationStrategy);

            $this->assign($value->getValue());
            $this->addValueAttribute($value->getValue());

            return;
        }

        $this->handleRequired();
        $this->handleOptional();
    }

    private function handlePath(array &$input): mixed
    {
        foreach ($this->property->getAttributes(Path::class) as $attribute) {
            /** @var Path $path */
            $path = $attribute->newInstance();
            $value = $path->extract($input);
            $key = $path->getKey();
            if ($value !== null && $key !== null) {
                unset($input[$key]);
            }

            return $value;
        }

        return null;
    }

    private function handleRejected(): void
    {
        foreach ($this->property->getAttributes(Reject::class) as $attribute) {
            /** @var Reject $reject */
            $reject = $attribute->newInstance();
            $reject->execute();
        }
    }

    private function handleRequired(): void
    {
        foreach ($this->property->getAttributes(Required::class) as $attribute) {
            /** @var Required $required */
            $required = $attribute->newInstance();
            $required->execute();
        }
    }

    private function handleOptional(): void
    {
        if ($this->hasDefaultValue && $this->defaultValue !== null) {
            $this->addValueAttribute($this->defaultValue);
            $this->assign($this->defaultValue);

            return;
        }

        foreach ($this->property->getAttributes(Optional::class) as $attribute) {
            $optional = $attribute->newInstance();
            $value = $optional->getValue($this->property);

            $this->assign($value);

            return;
        }

        if (!$this->hasDefaultValue) {
            $this->handleMissingRequiredValue();
        } else {
            $this->addValueAttribute($this->defaultValue);
            $this->assign($this->defaultValue);
        }
    }

    private function getPromotedConstructorParameter(?ReflectionMethod $constructor, string $name): ?ReflectionParameter
    {
        foreach ($constructor?->getParameters() ?? [] as $parameter) {
            if ($parameter->isPromoted() && $parameter->getName() === $name) {
                return $parameter;
            }
        }

        return null;
    }

    private function addValueAttribute(mixed $value): void
    {
        if ($this->instance instanceof AttributableData && (!is_null($value) || $this->hasDefaultValue)) {
            $this->instance->addAttribute($this->property->getName(), $value);
        }
    }

    private function assign(mixed $value): void
    {
        $instance = $this->property->isStatic() ? null : $this->instance;

        if (is_null($value) && !$this->property->getType()?->allowsNull()) {
            return;
        }

        $this->property->setValue($instance, $value);
    }

    private function setNames(): void
    {
        $names = [];

        foreach ($this->property->getAttributes(Name::class) as $attribute) {
            $name = $attribute->newInstance();
            $names[$name->getName()] = true;
        }

        if ($names === []) {
            $names[$this->property->getName()] = true;
        }

        foreach ($this->property->getAttributes(Alias::class) as $attribute) {
            $alias = $attribute->newInstance();
            $name = $alias->getName($this->property);

            if (is_array($name)) {
                foreach ($name as $singleName) {
                    $names[$singleName] = true;
                }
            } else {
                $names[$name] = true;
            }
        }

        $this->names = array_keys(
            array_reverse($names)
        );
    }

    private function handleMissingRequiredValue(): void
    {
        match (count($this->names)) {
            0, 1 => $this->validationStrategy->setFailure('Expected a value for {path}'),
            default => $this->validationStrategy->setFailure('Expected one of "' . implode(', ', $this->names) . '"'),
        };
    }
}
