<?php

namespace The42dx\Whatsapp\Abstracts;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Contracts\Enum;
use The42dx\Whatsapp\Factories\EntityCollectionFactory;

/**
 * Entity
 *
 * Abstract class for entities in the package.
 *
 * @package The42dx\Whatsapp\Abstracts
 *
 * @see \The42dx\Whatsapp\Contracts\Entity
 */
abstract class Entity implements ContractsEntity {
    public function __construct(?array $attributes = []) {
        $this->setAttributes($attributes);

        Log::debug('['. get_class($this) .'] created');
    }

    /**
     * toArray
     *
     * Convert the entity to an array
     *
     * @return array
     *
     * @see \Illuminate\Support\Collection
     * @see \The42dx\Whatsapp\Contracts\Entity
     */
    public function toArray(): array {
        $array = [];

        foreach ($this as $key => $value) {
            if ($value instanceof Entity) {
                $array[$key] = $value->toArray();

                continue;
            }

            if ($value instanceof Collection) {
                $array[$key] = $value->map(function ($item) {
                    return $item->toArray();
                })->toArray();

                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * toJson
     *
     * Convert the entity to a JSON string
     *
     * @return string
     */
    public function toJson(): string {
        return json_encode($this->toArray());
    }

    abstract public function setAttributes(?array $attributes = []): self;

    /**
     * setOrUpdateAttribute
     *
     * Check if provided attribute exists on the entity, and set or update it if so.
     *
     * @param string $attrName The entity attribute name
     * @param string $dataKey The key of the attribute in the data array
     * @param mixed $data The attribute value
     * @param class-string<\The42dx\Whatsapp\Contracts\Entity|The42dx\Whatsapp\Contracts\Enum>|null $class The class of the object or collection
     * @param bool $isCollection Whether the attribute is a collection
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the attribute does not exist on the entity
     */
    protected function setOrUpdateAttribute(string $attrName, string $dataKey, mixed $data, ?string $class = null, ?bool $isCollection = false): void {
        if (!property_exists($this, $attrName)) {
            throw new \InvalidArgumentException("Attribute '$attrName' does not exist on [" . get_class($this) . "].");
        }

        if (!$data || empty($data)) {
            $this->{$attrName} = null;

            return;
        }

        $oldValue = isset($this->{$attrName}) && !is_null($this->{$attrName}) && !empty($this->{$attrName}) ? $this->{$attrName} : null;
        $newValue = Arr::get($data, $dataKey) ?? null;
        $value    = !empty($newValue) ? $newValue : $oldValue;

        if ($isCollection) {
            $this->{$attrName} = EntityCollectionFactory::make($class, $value);

            return;
        }

        if (!is_null($class)) {
            if ((new \ReflectionClass($class))->isEnum()) {
                $this->{$attrName} = $value ? $class::from($value) : null;

                return;
            }

            if ((new $class) instanceof ContractsEntity) {
                $this->{$attrName} = new $class($value);

                return;
            }
        }

        $this->{$attrName} = $value;
    }

    /**
     * __get
     *
     * Get the entity attribute
     *
     * @param string $key The entity attribute
     * @return mixed The entity attribute value
     *
     * @see \Illuminate\Support\Collection
     * @see \The42dx\Whatsapp\Contracts\Entity
     */
    public function __get(string $key): mixed {
        return $this->$key;
    }
}
