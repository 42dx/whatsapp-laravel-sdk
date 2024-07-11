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
 * @package The42dx\Whatsapp\Entities
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 */
class EventEntity extends Entity implements ContractsEntity {
    /**
     * object
     *
     * The object type of the event
     *
     * @var \The42dx\Whatsapp\Enums\ObjectType|null
     */
    protected ObjectType|null $object;

    /**
     * entry
     *
     * The entry entity of the event
     *
     * @var \Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\EntryEntity
     */
    protected Collection|null $entry;

    /**
     * setAttributes
     *
     * Set the attributes of the event entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('object', 'object', $attributes, ObjectType::class);
        $this->setOrUpdateAttribute('entry', 'entry', $attributes, EntryEntity::class, true);

        return $this;
    }
}
