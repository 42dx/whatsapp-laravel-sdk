<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;

/**
 * VideoEntity
 *
 * Entity representing the video sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\MediaEntity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
 */
class VideoEntity extends MediaEntity implements Entity {
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
     * Set the attributes of the video entity
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
