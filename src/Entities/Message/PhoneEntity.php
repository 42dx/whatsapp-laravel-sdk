<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;

/**
 * PhoneEntity
 *
 * Entity representing the phone numbers of the contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#contacts-object
 */
class PhoneEntity extends Entity implements ContractsEntity {
    /**
     * number
     *
     * The phone number of the contact
     */
    protected ?string $number;

    /**
     * type
     *
     * The type of phone number
     *
     * @var string
     */
    protected ?ContactPropType $type;

    /**
     * waId
     *
     * The WhatsApp ID of the phone number
     */
    protected ?string $waId;

    /**
     * setAttributes
     *
     * Set the attributes of the phone entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('number', 'phone', $attributes);
        $this->setOrUpdateAttribute('type', 'type', $attributes, ContactPropType::class);
        $this->setOrUpdateAttribute('waId', 'wa_id', $attributes);

        return $this;
    }
}
