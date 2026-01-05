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
     */
    protected ?string $id;

    /**
     * from
     *
     * The phone number of the contact that sent the original message
     */
    protected ?string $from;

    /**
     * type
     *
     * The type of the context
     */
    protected ?ContextType $type;

    /**
     * setAttributes
     *
     * Set the attributes of the reply entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('id', 'id', $attributes);
        $this->setOrUpdateAttribute('from', 'from', $attributes);

        $this->type = isset($attributes['id']) ? $this->getContextType($attributes) : null;

        return $this;
    }

    /**
     * getContextType
     *
     * Get the context type from the attributes
     *
     *
     *
     * @see \The42dx\Whatsapp\Enums\ContextType
     */
    private function getContextType(?array $attributes = []): ContextType {
        $isForwarded = isset($attributes['forwarded']) && $attributes['forwarded'] ? ContextType::FWD : null;
        $isFrequentlyForwarded = isset($attributes['frequently_forwarded']) && $attributes['frequently_forwarded'] ? ContextType::F_FWD : null;
        $isAlreadySet = isset($this->type) && !is_null($this->type) ? $this->type : null;

        return $isForwarded ?? ($isFrequentlyForwarded ?? ($isAlreadySet ?? ContextType::STD));
    }
}
