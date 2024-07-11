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
    public function setAttributes(array $attributes = []): self {
        $this->audio       = isset($attributes['audio']) ? new AudioEntity($attributes['audio']) : (
            isset($this->audio) && !is_null($this->audio) ? $this->audio : null
        );
        $this->contacts    = isset($attributes['contacts']) ? EntityCollectionFactory::make(ContactEntity::class, $attributes['contacts']) : (
            isset($this->contacts) && !is_null($this->contacts) ? $this->contacts : null
        );
        $this->document    = isset($attributes['document']) ? new DocumentEntity($attributes['document']) : (
            isset($this->document) && !is_null($this->document) ? $this->document : null
        );
        $this->from        = isset($attributes['from']) ? $attributes['from'] : (
            isset($this->from) && !is_null($this->from) ? $this->from : null
        );
        $this->id          = isset($attributes['id']) ? $attributes['id'] : (
            isset($this->id) && !is_null($this->id) ? $this->id : null
        );
        $this->image       = isset($attributes['image']) ? new ImageEntity($attributes['image']) : (
            isset($this->image) && !is_null($this->image) ? $this->image : null
        );
        $this->location    = isset($attributes['location']) ? new LocationEntity($attributes['location']) : (
            isset($this->location) && !is_null($this->location) ? $this->location : null
        );
        $this->reaction    = isset($attributes['reaction']) ? new ReactionEntity($attributes['reaction']) : (
            isset($this->reaction) && !is_null($this->reaction) ? $this->reaction : null
        );
        $this->context       = isset($attributes['context']) ? new ContextEntity($attributes['context']) : (
            isset($this->context) && !is_null($this->context) ? $this->context : null
        );
        $this->sticker     = isset($attributes['sticker']) ? new StickerEntity($attributes['sticker']) : (
            isset($this->sticker) && !is_null($this->sticker) ? $this->sticker : null
        );
        $this->text        = isset($attributes['text']) && $attributes['text']['body'] ? $attributes['text']['body'] : (
            isset($this->text) && !is_null($this->text) ? $this->text : null
        );
        $this->timestamp   = isset($attributes['timestamp']) ? $attributes['timestamp'] : (
            isset($this->timestamp) && !is_null($this->timestamp) ? $this->timestamp : null
        );
        $this->type        = isset($attributes['type']) ? MessageType::from($attributes['type']) : (
            isset($this->type) && !is_null($this->type) ? $this->type : null
        );
        $this->video       = isset($attributes['video']) ? new VideoEntity($attributes['video']) : (
            isset($this->video) && !is_null($this->video) ? $this->video : null
        );

        $this->template    = null;
        $this->interactive = null;

        return $this;
    }
}
