<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;


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
    public function setAttributes(array $attributes = []): self {
        $this->url  = isset($attributes['url']) ? $attributes['url'] : (
            isset($this->url) && !is_null($this->url) ? $this->url : null
        );
        $this->type = isset($attributes['type']) ? ContactPropType::from($attributes['type']) : (
            isset($this->type) && !is_null($this->type) ? $this->type : null
        );

        return $this;
    }
}
