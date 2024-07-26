<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Enums\StickerType;

/**
 * StickerEntity
 *
 * Entity representing the sticker sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\MediaEntity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
 */
class StickerEntity extends MediaEntity implements Entity {
    /**
     * type
     *
     * The type of the sticker.
     *
     * @var \The42dx\Whatsapp\Enums\StickerType|null
     *
     * @see \The42dx\Whatsapp\Enums\StickerType
     */
    protected StickerType|null $type;

    /**
     * setAttributes
     *
     * Set the attributes of the sticker entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        parent::setAttributes($attributes);

        $this->type = !isset($attributes['animated']) ? null : (
            !((bool) $attributes['animated']) ? StickerType::STATIC : StickerType::ANIMATED
        );

        return $this;
    }
}
