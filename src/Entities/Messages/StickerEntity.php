<?php

namespace The42dx\Whatsapp\Entities\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;

/**
 * StickerEntity
 *
 * Entity representing the sticker sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\MediaEntity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
 */
class StickerEntity extends MediaEntity implements Entity {}
