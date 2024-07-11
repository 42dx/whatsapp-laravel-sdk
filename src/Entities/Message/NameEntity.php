<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * NameEntity
 *
 * Entity representing the name of the contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
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
     *
     * @var string|null
     */
    protected string|null $first;

    /**
     * formatted
     *
     * The formatted name of the contact
     *
     * @var string|null
     */
    protected string|null $formatted;

    /**
     * last
     *
     * The last name of the contact
     *
     * @var string|null
     */
    protected string|null $last;

    /**
     * middle
     *
     * The middle name of the contact
     *
     * @var string|null
     */
    protected string|null $middle;

    /**
     * prefix
     *
     * The prefix of the contact name
     *
     * @var string|null
     */
    protected string|null $prefix;

    /**
     * suffix
     *
     * The suffix of the contact name
     *
     * @var string|null
     */
    protected string|null $suffix;

    /**
     * setAttributes
     *
     * Set the attributes of the name entity
     *
     * @param array $attributes
     *
     * @return self
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
