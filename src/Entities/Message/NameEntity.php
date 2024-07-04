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
    public function setAttributes(array $attributes = []): self {
        $this->first     = isset($attributes['first_name']) ? $attributes['first_name'] : (
            isset($this->first) && !is_null($this->first) ? $this->first : null
        );
        $this->formatted = isset($attributes['formatted_name']) ? $attributes['formatted_name'] : (
            isset($this->formatted) && !is_null($this->formatted) ? $this->formatted : null
        );
        $this->last      = isset($attributes['last_name']) ? $attributes['last_name'] : (
            isset($this->last) && !is_null($this->last) ? $this->last : null
        );
        $this->middle    = isset($attributes['middle_name']) ? $attributes['middle_name'] : (
            isset($this->middle) && !is_null($this->middle) ? $this->middle : null
        );
        $this->prefix    = isset($attributes['name-prefix']) ? $attributes['name-prefix'] : (
            isset($this->prefix) && !is_null($this->prefix) ? $this->prefix : null
        );
        $this->suffix    = isset($attributes['name_suffix']) ? $attributes['name_suffix'] : (
            isset($this->suffix) && !is_null($this->suffix) ? $this->suffix : null
        );

        return $this;
    }
}
