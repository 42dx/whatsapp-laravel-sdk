<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * ButtonEntity
 *
 * Entity representing the button element when clicked by the user in a message sent to the Whatsapp contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#location-object
 */
class ButtonEntity extends Entity implements ContractsEntity {
    /**
     * text
     *
     * The text of the button clicked by the user
     */
    protected ?string $text;

    /**
     * payload
     *
     * The payload of the button clicked by the user
     */
    protected ?string $payload;

    /**
     * setAttributes
     *
     * Set the attributes of the button entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('text', 'text', $attributes);
        $this->setOrUpdateAttribute('payload', 'payload', $attributes);

        return $this;
    }
}
