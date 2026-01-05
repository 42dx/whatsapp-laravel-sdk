<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * LocationEntity
 *
 * Entity representing the location sent to the Whatsapp contact
 *
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
     */
    protected ?float $latitude;

    /**
     * longitude
     *
     * The longitude of the location
     */
    protected ?float $longitude;

    /**
     * address
     *
     * The address of the location
     */
    protected ?string $address;

    /**
     * name
     *
     * The name of the location
     */
    protected ?string $name;

    /**
     * url
     *
     * The URL of the location
     */
    protected ?string $url;

    /**
     * setAttributes
     *
     * Set the attributes of the location entity
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
