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
