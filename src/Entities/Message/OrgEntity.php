<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * OrgEntity
 *
 * Entity representing the organization sent to the Whatsapp contact
 *
 * @package The42dx\Whatsapp\Entities\Messages
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 * @see https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#contacts-object
 */
class OrgEntity extends Entity implements ContractsEntity {
    /**
     * company
     *
     * Name of the contact's company.
     *
     * @var string|null
     */
    protected string|null $company;

    /**
     * department
     *
     * Name of the contact's department.
     *
     * @var string|null
     */
    protected string|null $department;

    /**
     * title
     *
     * Contact's business title.
     *
     * @var string|null
     */
    protected string|null $title;

    /**
     * setAttributes
     *
     * Set the attributes of the org entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('company', 'company', $attributes);
        $this->setOrUpdateAttribute('department', 'department', $attributes);
        $this->setOrUpdateAttribute('title', 'title', $attributes);

        return $this;
    }
}
