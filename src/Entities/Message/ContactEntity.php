<?php

namespace The42dx\Whatsapp\Entities\Message;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * ContactEntity
 *
 * Entity representing the contacts sent to the Whatsapp contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#contacts-object
 */
class ContactEntity extends Entity implements ContractsEntity {
    /**
     * addresses
     *
     * The contact's addresses
     *
     * @var Illuminate\Support\Collection|null
     */
    protected ?Collection $addresses;

    /**
     * birthday
     *
     * The contact's birthday
     */
    protected ?string $birthday;

    /**
     * emails
     *
     * The contact's email addresses
     *
     * @var Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\EmailEntity|null
     */
    protected ?Collection $emails;

    /**
     * name
     *
     * The contact's name
     */
    protected ?NameEntity $name;

    /**
     * phones
     *
     * The contact's phone numbers
     *
     * @var Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\PhoneEntity
     */
    protected ?Collection $phones;

    /**
     * org
     *
     * The contact's organization
     */
    protected ?OrgEntity $org;

    /**
     * urls
     *
     * The contact URLs.
     *
     * @var Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\UrlEntity|null
     */
    protected ?Collection $urls;

    /**
     * setAttributes
     *
     * Set the attributes of the contact entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('addresses', 'addresses', $attributes, AddressEntity::class, true);
        $this->setOrUpdateAttribute('birthday', 'birthday', $attributes);
        $this->setOrUpdateAttribute('emails', 'emails', $attributes, EmailEntity::class, true);
        $this->setOrUpdateAttribute('name', 'name', $attributes, NameEntity::class);
        $this->setOrUpdateAttribute('org', 'org', $attributes, OrgEntity::class);
        $this->setOrUpdateAttribute('phones', 'phones', $attributes, PhoneEntity::class, true);
        $this->setOrUpdateAttribute('urls', 'urls', $attributes, UrlEntity::class, true);

        return $this;
    }
}
