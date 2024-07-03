<?php

namespace The42dx\Whatsapp\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ObjectType;
use The42dx\Whatsapp\Factories\EntityCollectionFactory;

/**
 * EventEntity
 *
 * The Whatsapp Business API event entity
 *
 * @package The42dx\Whatsapp\Entities
 *
 * @see \The42dx\Whatsapp\Entities\EntryEntity
 * @see \The42dx\Whatsapp\Enums\ObjectType
 */
class EventEntity extends Entity implements ContractsEntity {
    /**
     * object
     *
     * The object type of the event
     *
     * @var \The42dx\Whatsapp\Enums\ObjectType
     */
    protected ObjectType $object;

    /**
     * entry
     *
     * The entry entity of the event
     *
     * @var \Illuminate\Support\Collection
     *
     * @see \The42dx\Whatsapp\Entities\EntryEntity
     */
    protected Collection $entry;

    /**
     * setAttributes
     *
     * Set the attributes of the event entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->object = isset($attributes['object']) ? ObjectType::from($attributes['object']) : (
            isset($this->object) && !is_null($this->object) ? $this->object : null
        );
        $this->entry  = isset($attributes['entry']) ? EntityCollectionFactory::make(EntryEntity::class, $attributes['entry']) : (
            isset($this->entry) && !is_null($this->entry) ? $this->entry : null
        );

        return $this;
    }
}
