<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Traits\HasCaption;

class DocumentEntity extends MediaEntity implements Entity {
    use HasCaption { setAttributes as protected setCaptionAttributes; }

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
        $this->setCaptionAttributes($attributes);

        $this->filename = isset($attributes['filename']) ? $attributes['filename'] : (
            isset($this->filename) && !is_null($this->filename) ? $this->filename : null
        );

        return $this;
    }
}
