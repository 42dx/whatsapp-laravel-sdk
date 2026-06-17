<?php

use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\{ContextType, MessageType};
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class WhatsappMessageTest extends UnitTestCase {
    private WhatsappMessage $model;

    protected function setUp(): void {
        parent::setUp();

        $this->model = new WhatsappMessage;
        $this->model->payload = [
            ['type' => MessageType::LOCATION->value, 'value' => 1],
            ['type' => MessageType::REACTION->value, 'value' => 1],
            ['type' => MessageType::REACTION->value, 'value' => 2],
            ['type' => ContextType::REPLY->value, 'value' => 1],
        ];
    }

    public static function payloadFilterDataset(): array {
        return [
            'single message type filtering' => [MessageType::REACTION, 2],
            'single context type filtering' => [ContextType::REPLY, 1],
            'array of types filtering' => [[MessageType::REACTION, ContextType::REPLY], 3],
            'type not found on payload' => [MessageType::AUDIO, 0],
            'array of types not found on payload' => [[MessageType::AUDIO, ContextType::F_FWD], 0],
        ];
    }

    #[DataProvider('payloadFilterDataset')]
    public function test__get_payload_type__it_should_get_only_payload_data_that_matches_provided_payload_types(array|ContextType|MessageType $param, int $expectedCount): void {
        $filtered = $this->model->getPayloadType($param);

        $this->assertCount($expectedCount, $filtered);
    }

    #[DataProvider('payloadFilterDataset')]
    public function test__get_payload_without_type__it_should_get_only_payload_data_that_does_not_matches_provided_payload_types(array|ContextType|MessageType $param, int $removedCount): void {
        $filtered = $this->model->getPayloadWithoutType($param);

        $this->assertCount(count($this->model->payload) - $removedCount, $filtered);
    }
}
