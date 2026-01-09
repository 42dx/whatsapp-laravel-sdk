<?php

namespace The42dx\Whatsapp\Contracts;

/**
 * Entity
 *
 * Interface for entities in the package.
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 */
interface Entity {
    public function __construct(?array $attributes = []);

    /**
     * __get
     *
     * Get the entity attribute
     *
     * @param  string  $key  The entity attribute
     * @return mixed The entity attribute value
     *
     * @see \Illuminate\Support\Collection
     * @see \The42dx\Whatsapp\Contracts\Entity
     */
    public function __get(string $key): mixed;

    /**
     * setAttributes
     *
     * Set the entity attributes
     *
     * * @param  array|null  $attributes  The attributes to set
     */
    public function setAttributes(?array $attributes = []): self;

    /**
     * toArray
     *
     * Convert the entity to an array
     *
     *
     * @see \Illuminate\Support\Collection
     * @see \The42dx\Whatsapp\Contracts\Entity
     */
    public function toArray(): array;

    /**
     * toJson
     *
     * Convert the entity to a JSON string
     */
    public function toJson(): string;
}
