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
     * fileSize
     *
     * The size of the media file sent
     *
     * @var string|null
     */
    protected int|null $fileSize;

    /**
     * hash
     *
     * The hash of the audio file
     *
     * @var string|null
     */
    protected string|null $hash;

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

    /**
     * mimeType
     *
     * The MIME type of the audio file
     *
     * @var string|null
     */
    protected string|null $mimeType;

    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('id', 'id', $attributes);
        $this->setOrUpdateAttribute('mimeType', 'mime_type', $attributes);
        $this->setOrUpdateAttribute('hash', 'sha256', $attributes);

        $this->getMediaLink();

        return $this;
    }

    /**
     * getMediaLink
     *
     * Get the media link of the media sent
     *
     * @return string
     */
    private function getMediaLink(): void {
        $result = [];
        // Todo: Implement the logic to get the media link
        // Request the media link from the Whatsapp API. Below is an example of the response
        // {
        //     "url": "https://lookaside.fbsbx.com/whatsapp_business/attachments/?mid=804086435201373&ext=1720570800&hash=ATu_egbaFdMLm9eRdxqTU5Xj4-cFV5L91WhoOeR73SDLDg",
        //     "mime_type": "audio/ogg",
        //     "sha256": "fc6e2332bb1181b0845de86db67d11be57ccdc3c3194ca215f867c97c5e69610",
        //     "file_size": 6393,
        //     "id": "804086435201373",
        //     "messaging_product": "whatsapp"
        // }

        $this->fileSize = isset($result['file_size']) && !is_null($result['file_size']) ? $result['file_size'] : null;
        $this->link     = isset($result['url']) && !is_null($result['url']) ? $result['url'] : null;
    }
}
