<?php

namespace The42dx\Whatsapp\Entities\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;

/**
 * AddressEntity
 *
 * Entity representing the address sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#contacts-object
 */
class AddressEntity extends Entity implements ContractsEntity {
    /**
     * city
     *
     * The city of the address
     *
     * @var string|null
     */
    protected string|null $city;

    /**
     * countryCode
     *
     * The country code of the address
     *
     * @var string|null
     */
    protected string|null $countryCode;

    /**
     * country
     *
     * The country of the address
     *
     * @var string|null
     */
    protected string|null $country;

    /**
     * state
     *
     * The state of the address
     *
     * @var string|null
     */
    protected string|null $state;

    /**
     * street
     *
     * The street of the address
     *
     * @var string|null
     */
    protected string|null $street;

    /**
     * type
     *
     * The type of the address
     *
     * @var \The42dx\Whatsapp\Enums\ContactPropType|null
     */
    protected ContactPropType|null $type;

    /**
     * zip
     *
     * The zip code of the address
     *
     * @var string|null
     */
    protected string|null $zip;

    /**
     * setAttributes
     *
     * Set the attributes of the address entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->city        = isset($attributes['city']) ? $attributes['city'] : (
            isset($this->city) && !is_null($this->city) ? $this->city : null
        );
        $this->countryCode = isset($attributes['country_code']) ? $attributes['country_code'] : (
            isset($this->countryCode) && !is_null($this->countryCode) ? $this->countryCode : null
        );
        $this->country     = isset($attributes['country']) ? $attributes['country'] : (
            isset($this->country) && !is_null($this->country) ? $this->country : null
        );
        $this->state       = isset($attributes['state']) ? $attributes['state'] : (
            isset($this->state) && !is_null($this->state) ? $this->state : null
        );
        $this->street      = isset($attributes['street']) ? $attributes['street'] : (
            isset($this->street) && !is_null($this->street) ? $this->street : null
        );
        $this->type        = isset($attributes['type']) ? ContactPropType::from($attributes['type']) : (
            isset($this->type) && !is_null($this->type) ? $this->type : null
        );
        $this->zip         = isset($attributes['zip']) ? $attributes['zip'] : (
            isset($this->zip) && !is_null($this->zip) ? $this->zip : null
        );

        return $this;
    }
}
