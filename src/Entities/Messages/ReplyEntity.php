<?php

namespace The42dx\Whatsapp\Entities\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * ReplyEntity
 *
 * Entity representing the reply sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#mensagem-responder--
 */
class ReplyEntity extends Entity implements ContractsEntity {
    /**
     * id
     *
     * The ID of the message being replied to
     *
     * @var string|null $id
     */
    protected string|null $id;

    /**
     * from
     *
     * The phone number of the contact that sent the message being replied to
     *
     * @var string|null $from
     */
    protected string|null $from;

    /**
     * setAttributes
     *
     * Set the attributes of the reply entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->id   = isset($attributes['id']) ? $attributes['id'] : (
            isset($this->id) && !is_null($this->id) ? $this->id : null
        );
        $this->from = isset($attributes['from']) ? $attributes['from'] : (
            isset($this->from) && !is_null($this->from) ? $this->from : null
        );

        return $this;
    }
}
