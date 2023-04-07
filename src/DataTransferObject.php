<?php

declare(strict_types=1);

namespace Entense\Extractor;

use Entense\Extractor\Annotation\Contracts\Finalize;
use Entense\Extractor\Annotation\{SelfValidation, ValidationStrategy};
use Entense\Extractor\Contracts\DataTransfer;
use Entense\Extractor\Failure\{FailureCollection, FailureHandler};
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

final class DataTransferObject
{
    private ReflectionClass $reflection;

    private DataTransfer $object;
    private ?ReflectionMethod $constructor;
    private ValidationStrategy $validationStrategy;

    public function __construct(string $class)
    {
        $this->reflection = new ReflectionClass($class);
        $this->constructor = $this->reflection->getConstructor();
        $this->createInstance();
        $this->createValidationStrategy();
    }

    public function from(array &$input): self
    {
        $this->validationStrategy->pushPath($this->reflection->getShortName());

        foreach ($this->reflection->getProperties() as $property) {
            $this->setPropertyValue($property, $input);
        }

        $this->validationStrategy->popPath();

        $this->finalize($input);

        return $this;
    }

    public function getProperty($key)
    {
        return $this->reflection->getProperty($key);
    }

    public function setPropertyValue($property, $input): void
    {
        $this->validationStrategy->pushPath($property->getName());

        $dtp = new DataTransferProperty($property, $this);

        if ($dtp->isIgnored()) {
            $dtp->ignoreIn($input);
        } else {
            $dtp->setValueFrom($input);
        }

        $this->validationStrategy->popPath();
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws ReflectionException
     */
    private function finalize(array $input): void
    {
        $this->validationStrategy->handle();

        foreach ($this->reflection->getAttributes(Finalize::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $finalize = $attribute->newInstance();
            $finalize->finalize($input);
        }

        foreach ($this->reflection->getAttributes(SelfValidation::class) as $attribute) {
            $validation = $attribute->newInstance();
            $method = $this->reflection->getMethod($validation->getMethod());
            $method->invoke($this->object);
        }

        $this->object->boot();
    }

    public function getInstance(): DataTransfer
    {
        return $this->object;
    }

    public function getConstructor(): ?ReflectionMethod
    {
        return $this->constructor;
    }

    public function getValidationStrategy(): ValidationStrategy
    {
        return $this->validationStrategy;
    }

    private function createInstance(): void
    {
        if ($this->constructor === null || $this->constructor->getNumberOfRequiredParameters() === 0) {
            $this->object = $this->reflection->newInstance();
        } else {
            $this->object = $this->reflection->newInstanceWithoutConstructor();
        }
    }

    private function createValidationStrategy(): void
    {
        foreach ($this->reflection->getAttributes(ValidationStrategy::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $validationStrategy = $attribute->newInstance();
            $this->validationStrategy = $validationStrategy;
            break;
        }

        $this->validationStrategy ??= new ValidationStrategy(
            collection: new FailureCollection(),
            handler: new FailureHandler(),
            failFast: true
        );
    }
}
