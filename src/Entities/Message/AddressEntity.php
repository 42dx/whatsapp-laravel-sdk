<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Enums\ContactPropType;

/**
 * AddressEntity
 *
 * Entity representing the address sent to the Whatsapp contact
 *
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
     */
    protected ?string $city;

    /**
     * countryCode
     *
     * The country code of the address
     */
    protected ?string $countryCode;

    /**
     * country
     *
     * The country of the address
     */
    protected ?string $country;

    /**
     * state
     *
     * The state of the address
     */
    protected ?string $state;

    /**
     * street
     *
     * The street of the address
     */
    protected ?string $street;

    /**
     * type
     *
     * The type of the address
     */
    protected ?ContactPropType $type;

    /**
     * zip
     *
     * The zip code of the address
     */
    protected ?string $zip;

    /**
     * setAttributes
     *
     * Set the attributes of the address entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('city', 'city', $attributes);
        $this->setOrUpdateAttribute('countryCode', 'country_code', $attributes);
        $this->setOrUpdateAttribute('country', 'country', $attributes);
        $this->setOrUpdateAttribute('state', 'state', $attributes);
        $this->setOrUpdateAttribute('street', 'street', $attributes);
        $this->setOrUpdateAttribute('type', 'type', $attributes, ContactPropType::class);
        $this->setOrUpdateAttribute('zip', 'zip', $attributes);

        return $this;
    }
}
