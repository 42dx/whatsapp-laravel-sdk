<?php

namespace The42dx\Whatsapp\Tests\Unit\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\{DataProvider, DoesNotPerformAssertions};
use The42dx\Whatsapp\Entities\Message\{ContextEntity, MessageEntity, StatusEntity};
use The42dx\Whatsapp\Enums\{ContextType, MessageStatus, MessageType};
use The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleMessageMetadata;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class HandleMessageMetadataTest extends UnitTestCase {
    use HandleMessageMetadata;

    public static function msgStatusDataset(): array {
        return [
            MessageStatus::DELETED->value . ' status' => [MessageStatus::DELETED, 'whatsapp_deleted_at'],
            MessageStatus::DELIVERED->value . ' status' => [MessageStatus::DELIVERED, 'delivered_at'],
            MessageStatus::READ->value . ' status' => [MessageStatus::READ, 'read_at'],
            MessageStatus::SENT->value . ' status' => [MessageStatus::SENT, 'sent_at'],
            MessageStatus::FAILED->value . ' status' => [MessageStatus::FAILED, 'failed_at'],
        ];
    }

    public static function msgCtxDataset(): array {
        return [
            ContextType::REPLY->value . ' context' => [ContextType::REPLY, ['from' => '12312312312', 'id' => 'wamid.123123123']],
            ContextType::F_FWD->value . ' context' => [ContextType::F_FWD, ['from' => '12312312312', 'frequently_forwarded' => true]],
            ContextType::FWD->value . ' context' => [ContextType::FWD, ['from' => '12312312312', 'forwarded' => true]],
        ];
    }

    #[DataProvider('msgStatusDataset')]
    public function test__handle_status__it_should_update_message_status_and_relevant_timestamp(MessageStatus $status, string $timestampCol): void {
        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::PENDING)->make();

        $statusEntity = new StatusEntity(['id' => $messageModel->whatsapp_message_id, 'status' => $status->value]);
        $resultModel = $this->handleStatus($messageModel, $statusEntity);

        $this->assertEquals($status->value, $resultModel->status);
        $this->assertNotNull($resultModel->{$timestampCol});
    }

    #[DoesNotPerformAssertions]
    public function test__handle_status__it_should_warn_if_message_status_was_not_recognized(): void {
        Log::spy();

        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::PENDING)->make();
        $statusEntity = new StatusEntity(['id' => $messageModel->whatsapp_message_id, 'status' => MessageStatus::WARNING->value]);

        Log::shouldReceive('warning')
            ->once()
            ->with('Unhandled message status: ' . $statusEntity->status->value);

        $this->handleStatus($messageModel, $statusEntity);
    }

    #[DataProvider('msgCtxDataset')]
    public function test__handle_context__it_should_update_message_payload_with_context(ContextType $ctxType, array $attrs): void {
        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::READ)->make();
        $ctxEntity = new ContextEntity($attrs);

        $this->handleContext($messageModel, $ctxEntity);

        $this->assertCount(1, $messageModel->payload);
        $this->assertEquals($ctxType->value, $messageModel->payload[0]['type']);
    }

    #[DoesNotPerformAssertions]
    public function test__handle_context__it_should_log_if_message_already_have_a_context_payload(): void {
        Log::spy();

        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::READ)->make();
        $messageModel->payload = [['type' => ContextType::REPLY->value, 'context' => '123123', 'from' => '12312312312']];
        $ctxEntity = new ContextEntity(['id' => $messageModel->whatsapp_message_id, 'from' => '12312312312']);

        Log::shouldReceive('info')
            ->once()
            ->with('Message already has context, skipping context handling');

        $this->handleContext($messageModel, $ctxEntity);
    }

    public function test__handle_reaction__it_should_add_reaction_payload_if_emoji_is_provided(): void {
        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::READ)->make();
        $messageEntity = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '11111111111', 'reaction' => ['emoji' => '👍', 'message_id' => 'wamid.1111111']]);
        $messageEntity2 = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '22222222222', 'reaction' => ['emoji' => '❤️', 'message_id' => 'wamid.1111111']]);

        $this->handleReaction($messageModel, $messageEntity);

        $this->assertCount(1, $messageModel->payload);
        $this->assertEquals(MessageType::REACTION->value, $messageModel->payload[0]['type']);
        $this->assertEquals($messageEntity->reaction->emoji, $messageModel->payload[0]['emoji']);

        $this->handleReaction($messageModel, $messageEntity2);
        $this->assertCount(2, $messageModel->payload);
        $this->assertEquals(MessageType::REACTION->value, $messageModel->payload[1]['type']);
        $this->assertEquals($messageEntity2->reaction->emoji, $messageModel->payload[1]['emoji']);
    }

    public function test__handle_reaction__it_should_remove_reaction_payload_if_empty_string_is_provided_as_emoji(): void {
        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::READ)->make();
        $messageEntity = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '11111111111', 'reaction' => ['emoji' => '👍', 'message_id' => 'wamid.1111111']]);
        $messageEntity2 = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '22222222222', 'reaction' => ['emoji' => '❤️', 'message_id' => 'wamid.1111111']]);

        $this->handleReaction($messageModel, $messageEntity);

        $this->assertCount(1, $messageModel->payload);
        $this->assertEquals(MessageType::REACTION->value, $messageModel->payload[0]['type']);
        $this->assertEquals($messageEntity->reaction->emoji, $messageModel->payload[0]['emoji']);

        $this->handleReaction($messageModel, $messageEntity2);
        $this->assertCount(2, $messageModel->payload);
        $this->assertEquals(MessageType::REACTION->value, $messageModel->payload[1]['type']);
        $this->assertEquals($messageEntity2->reaction->emoji, $messageModel->payload[1]['emoji']);

        $removeEntity = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '11111111111', 'reaction' => ['emoji' => '', 'message_id' => 'wamid.1111111']]);
        $removeEntity2 = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '22222222222', 'reaction' => ['emoji' => '', 'message_id' => 'wamid.1111111']]);

        $this->handleReaction($messageModel, $removeEntity);
        $this->assertCount(1, $messageModel->payload);
        $this->assertEquals(MessageType::REACTION->value, $messageModel->payload[0]['type']);
        $this->assertEquals($messageEntity2->reaction->emoji, $messageModel->payload[0]['emoji']);

        $this->handleReaction($messageModel, $removeEntity2);
        $this->assertCount(0, $messageModel->payload);
    }
}
