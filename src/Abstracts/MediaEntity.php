<?php

namespace The42dx\Whatsapp\Abstracts;

use The42dx\Whatsapp\Abstracts\Entity;

/**
 * MediaEntity
 *
 * Entity representing the media sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Abstracts
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
 */
abstract class MediaEntity extends Entity {
    /**
     * id
     *
     * The unique identifier of the media sent
     *
     * @var string|null
     */
    protected string|null $id;

    /**
     * link
     *
     * The link to the media sent
     *
     * @var string|null
     */
    protected string|null $link;

    public function setAttributes(array $attributes = []): self {
        $this->id   = isset($attributes['id']) ? $attributes['id'] : (
            isset($this->id) && !is_null($this->id) ? $this->id : null
        );
        $this->link = isset($attributes['link']) ? $attributes['link'] : (
            isset($this->link) && !is_null($this->link) ? $this->link : null
        );

        return $this;
    }
}
