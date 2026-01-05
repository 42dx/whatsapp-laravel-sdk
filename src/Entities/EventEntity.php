<?php

namespace The42dx\Whatsapp\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ObjectType;

/**
 * EventEntity
 *
 * The Whatsapp Business API event entity
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 */
class EventEntity extends Entity implements ContractsEntity {
    /**
     * object
     *
     * The object type of the event
     */
    protected ?ObjectType $object;

    /**
     * entries
     *
     * The entry entity of the event
     *
     * @see \The42dx\Whatsapp\Entities\EntryEntity
     */
    protected ?Collection $entries;

    /**
     * setAttributes
     *
     * Set the attributes of the event entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('object', 'object', $attributes, ObjectType::class);
        $this->setOrUpdateAttribute('entries', 'entry', $attributes, EntryEntity::class, true);

        return $this;
    }
}
