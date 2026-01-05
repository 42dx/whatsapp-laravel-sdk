<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;
use The42dx\Whatsapp\Entities\Traits\HasCaption;

/**
 * DocumentEntity
 *
 * Represents a document message sent to/from the Whatsapp Business API
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see \The42dx\Whatsapp\Entities\Message\MediaEntity
 * @see https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#media-object
 */
class DocumentEntity extends MediaEntity implements Entity {
    use HasCaption { setAttributes as protected setCaptionAttributes; }

    /**
     * filename
     *
     * The filename of the document.
     */
    protected ?string $filename;

    /**
     * setAttributes
     *
     * Set the attributes of the document entity
     */
    public function setAttributes(?array $attributes = []): self {
        parent::setAttributes($attributes);
        $this->setCaptionAttributes($attributes);

        $this->setOrUpdateAttribute('filename', 'filename', $attributes);

        return $this;
    }
}
