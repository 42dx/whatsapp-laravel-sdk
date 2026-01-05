<?php

namespace The42dx\Whatsapp\Entities\Traits;

/**
 * HasCaption
 *
 * Trait for entities that have a caption attribute
 */
trait HasCaption {
    /**
     * caption
     *
     * The caption of the media.
     */
    protected ?string $caption;

    /**
     * setAttributes
     *
     * Set the attributes of the media entity
     */
    public function setAttributes(?array $attributes = []): self {
        parent::setAttributes($attributes);

        $this->setOrUpdateAttribute('caption', 'caption', $attributes);

        return $this;
    }
}
