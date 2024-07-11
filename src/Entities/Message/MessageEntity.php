<?php

namespace The42dx\Whatsapp\Entities\Message;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\Message\{
    AudioEntity,
    ContactEntity,
    DocumentEntity,
    ImageEntity,
    LocationEntity,
    ReactionEntity,
    ContextEntity,
    StickerEntity,
};
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Factories\EntityCollectionFactory;

/**
 * MessageEntity
 *
 * Entity representing the message sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
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
     *
     * @var \The42dx\Whatsapp\Entities\Message\AudioEntity|null
     */
    protected AudioEntity|null $audio;

    /**
     * contacts
     *
     * The contacts sent to the Whatsapp contact
     *
     * @var \Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\ContactEntity
     */
    protected Collection|null $contacts;

    /**
     * document
     *
     * The document sent to the Whatsapp contact
     *
     * @var \The42dx\Whatsapp\Entities\Message\DocumentEntity|null
     */
    protected DocumentEntity|null $document;

    /**
     * from
     *
     * The phone number of the Whatsapp contact that sent the message
     *
     * @var string|null
     */
    protected string|null $from;

    /**
     * id
     *
     * The unique identifier of the message sent
     *
     * @var string|null
     */
    protected string|null $id;

    /**
     * image
     *
     * The image sent to the Whatsapp contact
     *
     * @var \The42dx\Whatsapp\Entities\Message\ImageEntity|null
     */
    protected ImageEntity|null $image;

    /**
     * location
     *
     * The location sent to the Whatsapp contact
     *
     * @var The42dx\Whatsapp\Entities\Message\LocationEntity|null
     */
    protected LocationEntity|null $location;

    /**
     * reaction
     *
     * The reaction to the message sent to the Whatsapp contact
     *
     * @var \The42dx\Whatsapp\Entities\Message\ReactionEntity|null
     */
    protected ReactionEntity|null $reaction;

    /**
     * context
     *
     * The context to the message sent to the Whatsapp contact
     *
     * @var \The42dx\Whatsapp\Entities\Message\ContextEntity|null
     */
    protected ContextEntity|null $context;

    /**
     * sticker
     *
     * The sticker sent to the Whatsapp contact
     *
     * @var \The42dx\Whatsapp\Entities\Message\StickerEntity|null
     */
    protected StickerEntity|null $sticker;

    /**
     * text
     *
     * The text message sent to the Whatsapp contact
     *
     * @var string|null
     */
    protected string|null $text;

    /**
     * timestamp
     *
     * The timestamp of the message sent
     *
     * @var string|null
     */
    protected string|null $timestamp;

    /**
     * type
     *
     * The type of message sent
     *
     * @var \The42dx\Whatsapp\Enums\MessageType|null
     */
    protected MessageType|null $type;

    /**
     * video
     *
     * The video sent to the Whatsapp contact
     *
     * @var \The42dx\Whatsapp\Entities\Message\VideoEntity|null
     */
    protected VideoEntity|null $video;

    protected null $template; // TODO: implement
    protected null $interactive; // TODO: implement

    /**
     * setAttributes
     *
     * Set the attributes of the message entity
     *
     * @param array $attributes
     *
     * @return self
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

        $this->template    = null;
        $this->interactive = null;

        return $this;
    }
}
