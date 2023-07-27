<?php

namespace Entense\Extractor\Transformers;

use BackedEnum;
use DateTimeInterface;
use Entense\Extractor\Annotation\Transform;
use Entense\Extractor\Contracts\AttributableData;
use Entense\Extractor\Contracts\TransformableData;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

class DataTransformer
{
    protected bool $transformValues;

    final public function __construct(bool $transformValues)
    {
        $this->transformValues = $transformValues;
    }

    final public static function create(bool $transformValues): self
    {
        return new self($transformValues);
    }

    final public function transform(TransformableData $data): array
    {
        if ($this->transformValues) {
            return $this->toArray($data);
        }

        return $this->all($data, false);
    }

    final public function toArray(TransformableData $data): array
    {
        return array_intersect_key($this->all($data), $this->getAttributesPayload($data));
    }

    protected function getAttributesPayload(TransformableData $data): array
    {
        return $data instanceof AttributableData ? $data->getAttributes() : [];
    }

    final public function all(TransformableData $data, int|false $conditions = ReflectionProperty::IS_PUBLIC): array
    {
        $output = [];
        $class = new ReflectionClass($data::class);

        if ($conditions === false) {
            $properties = $class->getProperties();
        } else {
            $properties = $class->getProperties($conditions);
        }

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $value = null;

            if ($property->isInitialized($data)) {
                $value = $property->getValue($data);
            }

            $name = $property->getName();

            $output[$name] = $this->convertValueToArray($value, $property) ?? $value;
        }

        return $output;
    }

    final protected function convertValueToArray(mixed $value, ReflectionProperty $property)
    {
        if (is_array($value)) {
            array_walk($value, function (&$children) {
                if ($children instanceof TransformableData) {
                    $children = $children->transform($this->transformValues);
                }
            });
        }

        foreach ($property->getAttributes(Transform::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $value = $attribute->newInstance()->transform($value, $property);
        }

        if ($value instanceof TransformableData) {
            $value = $value->transform($this->transformValues);
        }

        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if ($value instanceof DateTimeInterface) {
            $value = $value->format(DATE_W3C);
        }

        return $value;
    }
}
