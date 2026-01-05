<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * NameEntity
 *
 * Entity representing the name of the contact
 *
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#contacts-object
 */
class NameEntity extends Entity implements ContractsEntity {
    /**
     * first
     *
     * The first name of the contact
     */
    protected ?string $first;

    /**
     * formatted
     *
     * The formatted name of the contact
     */
    protected ?string $formatted;

    /**
     * last
     *
     * The last name of the contact
     */
    protected ?string $last;

    /**
     * middle
     *
     * The middle name of the contact
     */
    protected ?string $middle;

    /**
     * prefix
     *
     * The prefix of the contact name
     */
    protected ?string $prefix;

    /**
     * suffix
     *
     * The suffix of the contact name
     */
    protected ?string $suffix;

    /**
     * setAttributes
     *
     * Set the attributes of the name entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('first', 'first_name', $attributes);
        $this->setOrUpdateAttribute('formatted', 'formatted_name', $attributes);
        $this->setOrUpdateAttribute('last', 'last_name', $attributes);
        $this->setOrUpdateAttribute('middle', 'middle_name', $attributes);
        $this->setOrUpdateAttribute('prefix', 'name-prefix', $attributes);
        $this->setOrUpdateAttribute('suffix', 'name_suffix', $attributes);

        return $this;
    }
}
