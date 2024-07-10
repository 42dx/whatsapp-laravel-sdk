<?php

namespace The42dx\Whatsapp\Traits;

trait HasCaption {
    /**
     * caption
     *
     * The caption of the media.
     *
     * @var string|null
     */
    protected string|null $caption;

    /**
     * setAttributes
     *
     * Set the attributes of the media entity
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
