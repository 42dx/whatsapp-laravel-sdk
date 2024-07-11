<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * LocationEntity
 *
 * Entity representing the location sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#location-object
 */
class LocationEntity extends Entity implements ContractsEntity {
    /**
     * latitude
     *
     * The latitude of the location
     *
     * @var float|null
     */
    protected float|null $latitude;

    /**
     * longitude
     *
     * The longitude of the location
     *
     * @var float|null
     */
    protected float|null $longitude;

    /**
     * address
     *
     * The address of the location
     *
     * @var string|null
     */
    protected string|null $address;

    /**
     * name
     *
     * The name of the location
     *
     * @var string|null
     */
    protected string|null $name;

    /**
     * url
     *
     * The URL of the location
     *
     * @var string|null
     */
    protected string|null $url;

    /**
     * setAttributes
     *
     * Set the attributes of the location entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('address', 'address', $attributes);
        $this->setOrUpdateAttribute('latitude', 'latitude', $attributes);
        $this->setOrUpdateAttribute('longitude', 'longitude', $attributes);
        $this->setOrUpdateAttribute('name', 'name', $attributes);
        $this->setOrUpdateAttribute('url', 'url', $attributes);

        return $this;
    }
}
