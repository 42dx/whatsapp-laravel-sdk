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
     */
    protected ?string $id;

    /**
     * recipientNumber
     *
     * The recipient number of the message status refers to
     */
    protected ?string $recipientNumber;

    /**
     * status
     *
     * The status of the message
     *
     *
     * @see \The42dx\Whatsapp\Enums\MessageStatus
     */
    protected ?MessageStatus $status;

    /**
     * timestamp
     *
     * The timestamp of the message status
     */
    protected ?string $timestamp;

    /**
     * setAttributes
     *
     * Set the attributes of the entity
     *
     * @param  array|null  $attributes  The attributes to set
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
