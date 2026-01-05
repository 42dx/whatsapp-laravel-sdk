<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Traits\HasCaption;

/**
 * VideoEntity
 *
 * Entity representing the video sent to the Whatsapp contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\MediaEntity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
 */
class VideoEntity extends MediaEntity implements Entity {
    use HasCaption { setAttributes as protected setCaptionAttributes; }

    /**
     * setAttributes
     *
     * Set the attributes of the video entity
     */
    public function setAttributes(?array $attributes = []): self {
        parent::setAttributes($attributes);
        $this->setCaptionAttributes($attributes);

        return $this;
    }
}
