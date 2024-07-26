<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\MessageStatus;

/**
 * StatusEntity
 *
 * Entity representing the status of the message sent to the Whatsapp contacts
 *
 * @package The42dx\Whatsapp\Entities\Message
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see \The42dx\Whatsapp\Enums\MessageStatus
 */
class StatusEntity extends Entity implements ContractsEntity {
    /**
     * id
     *
     * The ID of the message status refers to
     *
     * @var string|null
     */
    protected string|null $id;

    /**
     * recipientNumber
     *
     * The recipient number of the message status refers to
     *
     * @var string|null
     */
    protected string|null $recipientNumber;

    /**
     * status
     *
     * The status of the message
     *
     * @var \The42dx\Whatsapp\Enums\MessageStatus|null
     *
     * @see \The42dx\Whatsapp\Enums\MessageStatus
     */
    protected MessageStatus|null $status;

    /**
     * timestamp
     *
     * The timestamp of the message status
     *
     * @var string|null
     */
    protected string|null $timestamp;

    /**
     * setAttributes
     *
     * Set the attributes of the entity
     *
     * @param array|null $attributes The attributes to set
     * @return self The entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('id', 'id', $attributes);
        $this->setOrUpdateAttribute('recipientNumber', 'recipient_id', $attributes);
        $this->setOrUpdateAttribute('status', 'status', $attributes, MessageStatus::class);
        $this->setOrUpdateAttribute('timestamp', 'timestamp', $attributes);

        return $this;
    }
}
