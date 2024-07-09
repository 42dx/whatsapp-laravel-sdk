<?php

namespace The42dx\Whatsapp\Tests\Unit\Entities;

use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Abstracts\Entity;
use The42dx\Whatsapp\Entities\Changes\MessagesEntity;
use The42dx\Whatsapp\Entities\ChangesEntity;
use The42dx\Whatsapp\Enums\ApiEvent;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ChangesEntityTest extends UnitTestCase {
    public static function fieldTypeDataset(): array {
        return [
            'account_alerts event'                  => [ApiEvent::ACC_ALERTS->value, ApiEvent::ACC_ALERTS, null],
            'account_review_update event'           => [ApiEvent::ACC_REVIEW_UPDATE->value, ApiEvent::ACC_REVIEW_UPDATE, null],
            'account_update event'                  => [ApiEvent::ACC_UPDT->value, ApiEvent::ACC_UPDT, null],
            'business_capability_update event'      => [ApiEvent::BUSINESS_CAPABILITY_UPDT->value, ApiEvent::BUSINESS_CAPABILITY_UPDT, null],
            'business_status_update event'          => [ApiEvent::BUSINESS_STATUS_UPDT->value, ApiEvent::BUSINESS_STATUS_UPDT, null],
            'campaign_status_update event'          => [ApiEvent::CAMPAIGN_STATUS_UPDT->value, ApiEvent::CAMPAIGN_STATUS_UPDT, null],
            'flows event'                           => [ApiEvent::FLOWS->value, ApiEvent::FLOWS, null],
            'message_echoes event'                  => [ApiEvent::MSG_ECHOES->value, ApiEvent::MSG_ECHOES, null],
            'message_template_quality_update event' => [ApiEvent::MSG_TPLT_QUALITY_UPDT->value, ApiEvent::MSG_TPLT_QUALITY_UPDT, null],
            'message_template_status_update event'  => [ApiEvent::MSG_TPLT_STATUS_UPDT->value, ApiEvent::MSG_TPLT_STATUS_UPDT, null],
            'messaging_handovers event'             => [ApiEvent::MSG_HANDOVERS->value, ApiEvent::MSG_HANDOVERS, null],
            'message event'                         => [ApiEvent::MSGS->value, ApiEvent::MSGS, MessagesEntity::class],
            'partner_solutions event'               => [ApiEvent::PARTNER_SOLUTIONS->value, ApiEvent::PARTNER_SOLUTIONS, null],
            'phone_number_name_update event'        => [ApiEvent::PHONE_NUM_NAME_UPDT->value, ApiEvent::PHONE_NUM_NAME_UPDT, null],
            'phone_number_quality_update event'     => [ApiEvent::PHONE_NUM_QUALITY_UPDT->value, ApiEvent::PHONE_NUM_QUALITY_UPDT, null],
            'security event'                        => [ApiEvent::SECURITY->value, ApiEvent::SECURITY, null],
            'template_category_update event'        => [ApiEvent::TEMPLATE_CAT_UPDT->value, ApiEvent::TEMPLATE_CAT_UPDT, null],
        ];
    }

    public function test_itShouldBeAnEntityInstanceObject() {
        $changes = new ChangesEntity([]);

        $this->assertIsObject($changes);
        $this->assertInstanceOf(Entity::class, $changes);
    }

    #[DataProvider('fieldTypeDataset')]
    public function test_itShouldCreateObjectWithCorrectAttributes(string $field, ApiEvent $expectedApiEvent, string|null $expectedValue) {
        $changes     = new ChangesEntity([
            'field' => $field,
            'value' => []
        ]);

        $this->assertIsObject($changes);

        $this->assertEquals($expectedApiEvent, $changes->field);

        if ($expectedValue === null) { // Remove conditional when all events are implemented
            $this->assertNull($changes->value);
            return;
        }

        $this->assertInstanceOf($expectedValue, $changes->value);
    }

    public function test_itShouldUpdateAttributes() {
        $changes = new ChangesEntity([]);

        $this->assertNull($changes->field);
        $this->assertNull($changes->value);

        $changes->setAttributes([
            'field' => ApiEvent::MSGS->value,
            'value' => []
        ]);

        $this->assertEquals(ApiEvent::MSGS, $changes->field);

        $this->assertInstanceOf(MessagesEntity::class, $changes->value);
    }
}
