<?php

namespace The42dx\Whatsapp\Entities\Changes;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\Changes\ContactsEntity;
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Factories\EntityCollectionFactory;

/**
 * MessagesEntity
 *
 * Entity representing the messages sent to the Whatsapp contacts
 *
 * @package The42dx\Whatsapp\Entities\Changes
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages
 */
class MessagesEntity extends Entity implements ContractsEntity {
    /**
     * contacts
     *
     * The Whatsapp contacts the message was sent to
     *
     * @var \Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\ContactsEntity
     */
    protected Collection|null $contacts;

    /**
     * messages
     *
     * The messages sent to the Whatsapp contacts
     *
     * @var \Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\MessageEntity
     */
    protected Collection|null $messages;

    /**
     * waId
     *
     * The Whatsapp ID of the contact
     *
     * @var string|null
     */
    protected string|null $waId;

    /**
     * phone
     *
     * The Whatsapp phone number of the contact
     *
     * @var string|null
     */
    protected string|null $phone;

    /**
     * setAttributes
     *
     * Set the attributes of the messages entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->waId     = isset($attributes['metadata']) && $attributes['metadata']['phone_number_id'] ? $attributes['metadata']['phone_number_id'] : (
            isset($this->waId) && !is_null($this->waId) ? $this->waId : null
        );
        $this->phone    = isset($attributes['metadata']) && $attributes['metadata']['display_phone_number'] ? $attributes['metadata']['display_phone_number'] : (
            isset($this->phone) && !is_null($this->phone) ? $this->phone : null
        );
        $this->contacts = isset($attributes['contacts']) ? EntityCollectionFactory::make(ContactsEntity::class, $attributes['contacts']) : (
            isset($this->contacts) && !is_null($this->contacts) ? $this->contacts : null
        );
        $this->messages = isset($attributes['messages']) ? EntityCollectionFactory::make(MessageEntity::class, $attributes['messages']) : (
            isset($this->messages) && !is_null($this->messages) ? $this->messages : null
        );

        return $this;
    }
}
