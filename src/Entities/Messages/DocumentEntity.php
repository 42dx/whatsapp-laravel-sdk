<?php

namespace The42dx\Whatsapp\Entities\Messages;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;

class DocumentEntity extends MediaEntity implements Entity {
    /**
     * caption
     *
     * The caption of the document.
     *
     * @var string|null
     */
    protected string|null $caption;

    /**
     * filename
     *
     * The filename of the document.
     *
     * @var string|null
     */
    protected string|null $filename;

    /**
     * setAttributes
     *
     * Set the attributes of the document entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        parent::setAttributes($attributes);

        $this->caption  = isset($attributes['caption']) ? $attributes['caption'] : (
            isset($this->caption) && !is_null($this->caption) ? $this->caption : null
        );
        $this->filename = isset($attributes['filename']) ? $attributes['filename'] : (
            isset($this->filename) && !is_null($this->filename) ? $this->filename : null
        );

        return $this;
    }
}
