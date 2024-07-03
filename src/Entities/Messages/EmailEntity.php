<?php

namespace The42dx\Whatsapp\Entities\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;

/**
 * EmailEntity
 *
 * Entity representing the email sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
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
     *
     * @var string|null
     */
    protected string|null $email;

    /**
     * type
     *
     * The type of the email address
     *
     * @var \The42dx\Whatsapp\Enums\ContactPropType|null
     */
    protected ContactPropType|null $type;

    /**
     * setAttributes
     *
     * Set the attributes of the email entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->email = isset($attributes['email']) ? $attributes['email'] : (
            isset($this->email) && !is_null($this->email) ? $this->email : null
        );
        $this->type  = isset($attributes['type']) ? ContactPropType::from($attributes['type']) : (
            isset($this->type) && !is_null($this->type) ? $this->type : null
        );

        return $this;
    }
}
