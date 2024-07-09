<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContextType;

/**
 * ContextEntity
 *
 * Entity representing the context of a message
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#mensagem-responder--
 */
class ContextEntity extends Entity implements ContractsEntity {
    /**
     * id
     *
     * The ID of the relevant message
     *
     * @var string|null $id
     */
    protected string|null $id;

    /**
     * from
     *
     * The phone number of the contact that sent the original message
     *
     * @var string|null $from
     */
    protected string|null $from;

    /**
     * type
     *
     * The type of the context
     *
     * @var \The42dx\Whatsapp\Enums\ContextType $type
     */
    protected ContextType $type;

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

        $this->type = $this->getContextType($attributes);

        return $this;
    }

    /**
     * getContextType
     *
     * Get the context type from the attributes
     *
     * @param array $attributes
     *
     * @return \The42dx\Whatsapp\Enums\ContextType
     *
     * @see \The42dx\Whatsapp\Enums\ContextType
     */
    private function getContextType(array $attributes): ContextType {
        $isForwarded           = isset($attributes['forwarded']) && $attributes['forwarded'] ? ContextType::FWD : null;
        $isFrequentlyForwarded = isset($attributes['frequently_forwarded']) && $attributes['frequently_forwarded'] ? ContextType::F_FWD : null;
        $isAlreadySet          = isset($this->type) && !is_null($this->type) ? $this->type : null;

        return $isForwarded ?? ($isFrequentlyForwarded ?? ($isAlreadySet ?? ContextType::STD));
    }
}
