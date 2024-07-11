<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\MediaEntity;
use The42dx\Whatsapp\Contracts\Entity;

/**
 * AudioEntity
 *
 * Represents an audio message sent to/from the Whatsapp Business API
 *
 * @package The42dx\Whatsapp\Entities\Message
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see \The42dx\Whatsapp\Entities\Message\MediaEntity
 * @see https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#media-object
 */
class AudioEntity extends MediaEntity implements Entity {}
