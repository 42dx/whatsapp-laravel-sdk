<?php

namespace The42dx\Whatsapp\Entities\Message;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\MessageType;

/**
 * MessageEntity
 *
 * Entity representing the message sent to the Whatsapp contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#objeto-de-mensagem
 */
class MessageEntity extends Entity implements ContractsEntity {
    /**
     * audio
     *
     * The audio message sent to the Whatsapp contact
     */
    protected ?AudioEntity $audio;

    /**
     * contacts
     *
     * The contacts sent to the Whatsapp contact
     *
     *
     * @see \The42dx\Whatsapp\Entities\Message\ContactEntity
     */
    protected ?Collection $contacts;

    /**
     * document
     *
     * The document sent to the Whatsapp contact
     */
    protected ?DocumentEntity $document;

    /**
     * from
     *
     * The phone number of the Whatsapp contact that sent the message
     */
    protected ?string $from;

    /**
     * id
     *
     * The unique identifier of the message sent
     */
    protected ?string $id;

    /**
     * image
     *
     * The image sent to the Whatsapp contact
     */
    protected ?ImageEntity $image;

    /**
     * location
     *
     * The location sent to the Whatsapp contact
     *
     * @var The42dx\Whatsapp\Entities\Message\LocationEntity|null
     */
    protected ?LocationEntity $location;

    /**
     * reaction
     *
     * The reaction to the message sent to the Whatsapp contact
     */
    protected ?ReactionEntity $reaction;

    /**
     * context
     *
     * The context to the message sent to the Whatsapp contact
     */
    protected ?ContextEntity $context;

    /**
     * sticker
     *
     * The sticker sent to the Whatsapp contact
     */
    protected ?StickerEntity $sticker;

    /**
     * text
     *
     * The text message sent to the Whatsapp contact
     */
    protected ?string $text;

    /**
     * timestamp
     *
     * The timestamp of the message sent
     */
    protected ?string $timestamp;

    /**
     * type
     *
     * The type of message sent
     */
    protected ?MessageType $type;

    /**
     * video
     *
     * The video sent to the Whatsapp contact
     */
    protected ?VideoEntity $video;

    protected null $template; // TODO: implement

    protected null $interactive; // TODO: implement

    /**
     * setAttributes
     *
     * Set the attributes of the message entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('audio', 'audio', $attributes, AudioEntity::class);
        $this->setOrUpdateAttribute('contacts', 'contacts', $attributes, ContactEntity::class, true);
        $this->setOrUpdateAttribute('document', 'document', $attributes, DocumentEntity::class);
        $this->setOrUpdateAttribute('from', 'from', $attributes);
        $this->setOrUpdateAttribute('id', 'id', $attributes);
        $this->setOrUpdateAttribute('image', 'image', $attributes, ImageEntity::class);
        $this->setOrUpdateAttribute('location', 'location', $attributes, LocationEntity::class);
        $this->setOrUpdateAttribute('reaction', 'reaction', $attributes, ReactionEntity::class);
        $this->setOrUpdateAttribute('context', 'context', $attributes, ContextEntity::class);
        $this->setOrUpdateAttribute('sticker', 'sticker', $attributes, StickerEntity::class);
        $this->setOrUpdateAttribute('text', 'text.body', $attributes);
        $this->setOrUpdateAttribute('timestamp', 'timestamp', $attributes);
        $this->setOrUpdateAttribute('type', 'type', $attributes, MessageType::class);
        $this->setOrUpdateAttribute('video', 'video', $attributes, VideoEntity::class);

        $this->template = null;
        $this->interactive = null;

        return $this;
    }
}
