<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;

/**
 * EmailEntity
 *
 * Entity representing the email sent to the Whatsapp contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#contacts-object
 */
class EmailEntity extends Entity implements ContractsEntity {
    /**
     * email
     *
     * The email address
     */
    protected ?string $email;

    /**
     * type
     *
     * The type of the email address
     */
    protected ?ContactPropType $type;

    /**
     * setAttributes
     *
     * Set the attributes of the email entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('email', 'email', $attributes);
        $this->setOrUpdateAttribute('type', 'type', $attributes, ContactPropType::class);

        return $this;
    }
}
