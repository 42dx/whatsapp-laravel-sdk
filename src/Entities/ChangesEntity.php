<?php

namespace The42dx\Whatsapp\Entities;

use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Contracts\Entity as ContractsEntity;
use The42dx\Whatsapp\Entities\Changes\MessagesEntity;
use The42dx\Whatsapp\Enums\ApiEvent;

/**
 * ChangesEntity
 *
 * Entity representing a change in the Whatsapp Business API event.
 *
 * @package The42dx\Whatsapp\Entities
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 * @see \The42dx\Whatsapp\Contracts\Entity
 */
class ChangesEntity extends Entity implements ContractsEntity {
    /**
     * ERR_UNSUPPORTED_CHANGE
     *
     * Error message for unsupported change field
     *
     * @var string
     */
    const ERR_UNSUPPORTED_CHANGE = 'Unsupported change field';

    /**
     * The field that was changed.
     *
     * @var \The42dx\Whatsapp\Enums\ApiEvent|null
     */
    protected ApiEvent|null $field;

    /**
     * The new value of the field.
     *
     * @var \The42dx\Whatsapp\Contracts\Entity|null
     */
    protected Entity|null $value;

    /**
     * Get the field that was changed.
     *
     * @param array $value The value of the field
     * @return string|null The field that was changed
     *
     * @see \The42dx\Whatsapp\Enums\ApiEvent
     * @see \The42dx\Whatsapp\Entities\Changes\MessagesEntity
     */
    private function getChangeValue(): ?string {
        switch ($this->field) {
            case ApiEvent::MSGS:
                return MessagesEntity::class;
            case ApiEvent::ACC_ALERTS:
            case ApiEvent::ACC_REVIEW_UPDATE:
            case ApiEvent::ACC_UPDT:
            case ApiEvent::BUSINESS_CAPABILITY_UPDT:
            case ApiEvent::BUSINESS_STATUS_UPDT:
            case ApiEvent::CAMPAIGN_STATUS_UPDT:
            case ApiEvent::FLOWS:
            case ApiEvent::MSG_ECHOES:
            case ApiEvent::MSG_HANDOVERS:
            case ApiEvent::MSG_TPLT_QUALITY_UPDT:
            case ApiEvent::MSG_TPLT_STATUS_UPDT:
            case ApiEvent::PARTNER_SOLUTIONS:
            case ApiEvent::PHONE_NUM_NAME_UPDT:
            case ApiEvent::PHONE_NUM_QUALITY_UPDT:
            case ApiEvent::SECURITY:
            case ApiEvent::TEMPLATE_CAT_UPDT:
            default:
                // Log::warn(self::ERR_UNSUPPORTED_CHANGE, [
                //     'field' => $this->field->value,
                //     'value' => $value
                // ]);

                return null;
        }
    }

    /**
     * setAttributes
     *
     * Set the attributes of the changes entity
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(?array $attributes = []): self {
        $this->setOrUpdateAttribute('field', 'field', $attributes, ApiEvent::class);
        $this->setOrUpdateAttribute('value', 'value', $attributes, $this->getChangeValue());

        return $this;
    }
}
