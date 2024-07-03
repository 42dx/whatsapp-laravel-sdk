<?php

namespace The42dx\Whatsapp\Entities\Changes;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * ContactEntity
 *
 * Entity representing the contacts object of the Whatsapp message
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 */
class ContactsEntity extends Entity implements ContractsEntity {
    /**
     * profile
     *
     * The profile of the contact
     *
     * @var string|null
     */
    protected string|null $name;

    /**
     * waId
     *
     * The Whatsapp ID of the contact
     *
     * @var string|null
     */
    protected string|null $waId;

    /**
     * setAttributes
     *
     * Set the attributes of the Contacts entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->name = isset($attributes['profile']) && isset($attributes['profile']['name']) ? $attributes['profile']['name'] : (
            isset($this->name) && !is_null($this->name) ? $this->name : null
        );
        $this->waId = isset($attributes['wa_id']) ? $attributes['wa_id'] : (
            isset($this->waId) && !is_null($this->waId) ? $this->name : null
        );

        return $this;
    }
}
