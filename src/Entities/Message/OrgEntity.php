<?php

namespace The42dx\Whatsapp\Entities\Message;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;

/**
 * OrgEntity
 *
 * Entity representing the organization sent to the Whatsapp contact
 *
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
     */
    protected ?string $company;

    /**
     * department
     *
     * Name of the contact's department.
     */
    protected ?string $department;

    /**
     * title
     *
     * Contact's business title.
     */
    protected ?string $title;

    /**
     * setAttributes
     *
     * Set the attributes of the org entity
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('company', 'company', $attributes);
        $this->setOrUpdateAttribute('department', 'department', $attributes);
        $this->setOrUpdateAttribute('title', 'title', $attributes);

        return $this;
    }
}
