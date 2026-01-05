<?php

namespace The42dx\Whatsapp\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * EntryEntity
 *
 * The Whatsapp Business API entry entity
 *
 *
 * @see \The42dx\Whatsapp\Entities\ChangesEntity
 */
class EntryEntity extends Entity implements ContractsEntity {
    /**
     * id
     *
     * The entry ID
     */
    protected ?string $id;

    /**
     * changes
     *
     * The changes made on the Whatsapp Business API
     *
     *
     * @see \The42dx\Whatsapp\Entities\ChangeEntity
     */
    protected ?Collection $changes;

    /**
     * setAttributes
     *
     * Set the attributes of the entry entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('id', 'id', $attributes);
        $this->setOrUpdateAttribute('changes', 'changes', $attributes, ChangesEntity::class, true);

        return $this;
    }
}
