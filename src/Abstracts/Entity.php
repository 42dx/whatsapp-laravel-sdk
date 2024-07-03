<?php

namespace The42dx\Whatsapp\Abstracts;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

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
    public function __construct(array $attributes = []) {
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

    abstract public function setAttributes(array $attributes = []): self;

    /**
     * __get
     *
     * Get the entity attribute
     *
     * @param string $key The entity attribute
     * @return \Illuminate\Support\Collection|\The42dx\Whatsapp\Contracts\Entity|string|int|float|null The entity attribute
     *
     * @see \Illuminate\Support\Collection
     * @see \The42dx\Whatsapp\Contracts\Entity
     */
    public function __get(string $key): Collection|ContractsEntity|string|int|float|null {
        return $this->$key;
    }
}
