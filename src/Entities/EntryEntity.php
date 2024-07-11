<?php

namespace The42dx\Whatsapp\Entities;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\ChangesEntity;

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
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('id', 'id', $attributes);
        $this->setOrUpdateAttribute('changes', 'changes', $attributes, ChangesEntity::class, true);

        return $this;
    }
}
