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
 * @package The42dx\Whatsapp\Entities\Messages
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
     *
     * @var string $number
     */
    protected string|null $number;

    /**
     * type
     *
     * The type of phone number
     *
     * @var string $type
     */
    protected ContactPropType|null $type;

    /**
     * waId
     *
     * The WhatsApp ID of the phone number
     *
     * @var string $waId
     */
    protected string|null $waId;

    /**
     * setAttributes
     *
     * Set the attributes of the phone entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->number = isset($attributes['phone']) ? $attributes['phone'] : (
            isset($this->number) && !is_null($this->number) ? $this->number : null
        );
        $this->type   = isset($attributes['type']) ? ContactPropType::from($attributes['type']) : (
            isset($this->type) && !is_null($this->type) ? $this->type : null
        );
        $this->waId   = isset($attributes['wa_id']) ? $attributes['wa_id'] : (
            isset($this->waId) && !is_null($this->waId) ? $this->waId : null
        );

        return $this;
    }
}
