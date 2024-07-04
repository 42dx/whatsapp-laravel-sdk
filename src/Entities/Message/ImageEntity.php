<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;

class ImageEntity extends MediaEntity implements Entity {
    /**
     * caption
     *
     * The caption of the document.
     *
     * @var string|null
     */
    protected string|null $caption;

    /**
     * setAttributes
     *
     * Set the attributes of the image entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        parent::setAttributes($attributes);

        $this->caption = isset($attributes['caption']) ? $attributes['caption'] : (
            isset($this->caption) && !is_null($this->caption) ? $this->caption : null
        );

        return $this;
    }
}
