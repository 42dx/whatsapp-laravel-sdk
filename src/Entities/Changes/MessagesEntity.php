<?php

namespace The42dx\Whatsapp\Entities\Changes;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\Message\{MessageEntity, StatusEntity};

/**
 * MessagesEntity
 *
 * Entity representing the messages sent to the Whatsapp contacts
 *
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
     *
     * @see \The42dx\Whatsapp\Entities\Message\ContactsEntity
     */
    protected ?Collection $contacts;

    /**
     * messages
     *
     * The messages sent to the Whatsapp contacts
     *
     *
     * @see \The42dx\Whatsapp\Entities\Message\MessageEntity
     */
    protected ?Collection $messages;

    /**
     * statuses
     *
     * The statuses of the messages sent to the Whatsapp contacts
     *
     *
     * @see \The42dx\Whatsapp\Entities\Message\MessageEntity
     */
    protected ?Collection $statuses;

    /**
     * waId
     *
     * The Whatsapp ID of the contact
     */
    protected ?string $waId;

    /**
     * phone
     *
     * The Whatsapp phone number of the contact
     */
    protected ?string $phone;

    /**
     * setAttributes
     *
     * Set the attributes of the messages entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('waId', 'metadata.phone_number_id', $attributes);
        $this->setOrUpdateAttribute('phone', 'metadata.display_phone_number', $attributes);
        $this->setOrUpdateAttribute('contacts', 'contacts', $attributes, ContactsEntity::class, true);
        $this->setOrUpdateAttribute('messages', 'messages', $attributes, MessageEntity::class, true);
        $this->setOrUpdateAttribute('statuses', 'statuses', $attributes, StatusEntity::class, true);

        return $this;
    }
}
