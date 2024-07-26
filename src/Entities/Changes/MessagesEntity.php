<?php

namespace The42dx\Whatsapp\Entities\Changes;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\Changes\ContactsEntity;
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Entities\Message\StatusEntity;

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
     * statuses
     *
     * The statuses of the messages sent to the Whatsapp contacts
     *
     * @var \Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\MessageEntity
     */
    protected Collection|null $statuses;

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
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('waId', 'metadata.phone_number_id', $attributes);
        $this->setOrUpdateAttribute('phone', 'metadata.display_phone_number', $attributes);
        $this->setOrUpdateAttribute('contacts', 'contacts', $attributes, ContactsEntity::class, true);
        $this->setOrUpdateAttribute('messages', 'messages', $attributes, MessageEntity::class, true);
        $this->setOrUpdateAttribute('statuses', 'statuses', $attributes, StatusEntity::class, true);

        return $this;
    }
}
