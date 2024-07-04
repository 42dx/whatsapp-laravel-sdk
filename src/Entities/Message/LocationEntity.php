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
    public function setAttributes(array $attributes = []): self {
        $this->address   = isset($attributes['address']) ? $attributes['address'] : (
            isset($this->address) && !is_null($this->address) ? $this->address : null
        );
        $this->latitude  = isset($attributes['latitude']) ? $attributes['latitude'] : (
            isset($this->latitude) && !is_null($this->latitude) ? $this->latitude : null
        );
        $this->longitude = isset($attributes['longitude']) ? $attributes['longitude'] : (
            isset($this->longitude) && !is_null($this->longitude) ? $this->longitude : null
        );
        $this->name      = isset($attributes['name']) ? $attributes['name'] : (
            isset($this->name) && !is_null($this->name) ? $this->name : null
        );
        $this->url       = isset($attributes['url']) ? $attributes['url'] : (
            isset($this->url) && !is_null($this->url) ? $this->url : null
        );

        return $this;
    }
}
