<?php

namespace The42dx\Whatsapp\Entities\Changes;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * ContactEntity
 *
 * Entity representing the contacts object of the Whatsapp message
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 */
class ContactsEntity extends Entity implements ContractsEntity {
    /**
     * profile
     *
     * The profile of the contact
     */
    protected ?string $name;

    /**
     * waId
     *
     * The Whatsapp ID of the contact
     */
    protected ?string $waId;

    /**
     * setAttributes
     *
     * Set the attributes of the Contacts entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('name', 'profile.name', $attributes);
        $this->setOrUpdateAttribute('waId', 'wa_id', $attributes);

        return $this;
    }
}
