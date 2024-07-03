<?php

namespace The42dx\Whatsapp\Entities\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * ReactionEntity
 *
 * Entity representing the reaction sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#objeto-reaction
 */
class ReactionEntity extends Entity implements ContractsEntity {
    /**
     * emoji
     *
     * The emoji that represents the reaction to the message
     *
     * @var string|null
     */
    protected string|null $emoji;

    /**
     * repliedId
     *
     * The unique identifier of the message that was reacted to
     *
     * @var string|null
     */
    protected string|null $repliedId;

    /**
     * setAttributes
     *
     * Set the attributes of the reaction entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->emoji     = isset($attributes['emoji']) ? $attributes['emoji'] : (
            isset($this->emoji) && !is_null($this->emoji) ? $this->emoji : null
        );
        $this->repliedId = isset($attributes['message_id']) ? $attributes['message_id'] : (
            isset($this->repliedId) && !is_null($this->repliedId) ? $this->repliedId : null
        );

        return $this;
    }
}
