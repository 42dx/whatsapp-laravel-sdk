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
     * @var \The42dx\Whatsapp\Entities\Message\AddressEntity|null
    */
    protected AddressEntity $addresses;

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
    protected UrlEntity|null $urls;

    /**
     * setAttributes
     *
     * Set the attributes of the contact entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes = []): self {
        $this->addresses = isset($attributes['addresses']) ? new AddressEntity($attributes['addresses']) : (
            isset($this->addresses) && !is_null($this->addresses) ? $this->addresses : null
        );
        $this->birthday  = isset($attributes['birthday']) ? $attributes['birthday'] : (
            isset($this->birthday) && !is_null($this->birthday) ? $this->birthday : null
        );
        $this->emails    = isset($attributes['emails']) ? EntityCollectionFactory::make(EmailEntity::class, $attributes['emails']) : (
            isset($this->emails) && !is_null($this->emails) ? $this->emails : null
        );
        $this->name      = isset($attributes['name']) ? new NameEntity($attributes['name']) : (
            isset($this->name) && !is_null($this->name) ? $this->name : null
        );
        $this->org       = isset($attributes['org']) ? new OrgEntity($attributes['org']) : (
            isset($this->org) && !is_null($this->org) ? $this->org : null
        );
        $this->phones    = isset($attributes['phone']) ? EntityCollectionFactory::make(PhoneEntity::class, $attributes['phone']) : (
            isset($this->phones) && !is_null($this->phones) ? $this->phones : null
        );
        $this->urls      = isset($attributes['urls']) ? EntityCollectionFactory::make(UrlEntity::class, $attributes['urls']) : (
            isset($this->urls) && !is_null($this->urls) ? $this->urls : null
        );

        return $this;
    }
}
