<?php

namespace The42dx\Whatsapp\Entities\Message;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\Message\{
    AddressEntity,
    EmailEntity,
    NameEntity,
    OrgEntity,
    PhoneEntity,
    UrlEntity,
};
use The42dx\Whatsapp\Factories\EntityCollectionFactory;

/**
 * ContactEntity
 *
 * Entity representing the contacts sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
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
    protected Collection|null $addresses;

    /**
     * birthday
     *
     * The contact's birthday
     *
     * @var string|null
    */
    protected string|null $birthday;

    /**
     * emails
     *
     * The contact's email addresses
     *
     * @var Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\EmailEntity|null
    */
    protected Collection|null $emails;

    /**
     * name
     *
     * The contact's name
     *
     * @var \The42dx\Whatsapp\Entities\Message\NameEntity|null
    */
    protected NameEntity|null $name;

    /**
     * phones
     *
     * The contact's phone numbers
     *
     * @var Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\PhoneEntity
    */
    protected Collection|null $phones;

    /**
     * org
     *
     * The contact's organization
     *
     * @var \The42dx\Whatsapp\Entities\Message\OrgEntity|null
    */
    protected OrgEntity|null $org;

    /**
     * urls
     *
     * The contact URLs.
     *
     * @var Illuminate\Support\Collection|null
     *
     * @see \The42dx\Whatsapp\Entities\Message\UrlEntity|null
    */
    protected Collection|null $urls;

    /**
     * setAttributes
     *
     * Set the attributes of the contact entity
     *
     * @param array $attributes
     *
     * @return self
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
