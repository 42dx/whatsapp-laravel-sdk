<?php

namespace The42dx\Whatsapp\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\ChangesEntity;
use The42dx\Whatsapp\Factories\EntityCollectionFactory;

/**
 * EntryEntity
 *
 * The Whatsapp Business API entry entity
 *
 * @package The42dx\Whatsapp\Entities
 *
 * @see \The42dx\Whatsapp\Entities\ChangesEntity
 */
class EntryEntity extends Entity implements ContractsEntity {
    /**
     * id
     *
     * The entry ID
     *
     * @var string
     *
     */
    protected string|null $id;

    /**
     * changes
     *
     * The changes made on the Whatsapp Business API
     *
     * @var \Illuminate\Support\Collection
     *
     * @see \The42dx\Whatsapp\Entities\ChangeEntity
     *
     */
    protected Collection|null $changes;

    /**
     * setAttributes
     *
     * Set the attributes of the entry entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->id      = isset($attributes['id']) ? $attributes['id'] : (
            isset($this->id) && !is_null($this->id) ? $this->id : null
        );
        $this->changes = isset($attributes['changes']) ? EntityCollectionFactory::make(ChangesEntity::class, $attributes['changes']) : (
            isset($this->changes) && !is_null($this->changes) ? $this->changes : null
        );

        return $this;
    }
}
