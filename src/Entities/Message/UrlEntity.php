<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;

/**
 * UrlEntity
 *
 * Entity representing the url sent by Whatsapp Business API
 *
 * @package The42dx\Whatsapp\Entities\Message
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#contact-prop-object
 */
class UrlEntity extends Entity implements ContractsEntity {
    /**
     * url
     *
     * The url
     *
     * @var string|null
     */
    protected string|null $url;

    /**
     * type
     *
     * The type of the url
     *
     * @var \The42dx\Whatsapp\Enums\ContactPropType|null
     */
    protected ContactPropType|null $type;

    /**
     * setAttributes
     *
     * Set the attributes of the url entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('url', 'url', $attributes);
        $this->setOrUpdateAttribute('type', 'type', $attributes, ContactPropType::class);

        return $this;
    }
}
