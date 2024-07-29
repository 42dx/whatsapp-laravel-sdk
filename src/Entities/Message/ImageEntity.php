<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Traits\HasCaption;

/**
 * ImageEntity
 *
 * Represents an image message sent to/from the Whatsapp Business API
 *
 * @package The42dx\Whatsapp\Entities\Message
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see \The42dx\Whatsapp\Entities\Message\MediaEntity
 * @see https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#media-object
 */
class ImageEntity extends MediaEntity implements Entity {
    use HasCaption { setAttributes as protected setCaptionAttributes; }

    /**
     * setAttributes
     *
     * Set the attributes of the video entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        parent::setAttributes($attributes);
        $this->setCaptionAttributes($attributes);

        return $this;
    }
}
