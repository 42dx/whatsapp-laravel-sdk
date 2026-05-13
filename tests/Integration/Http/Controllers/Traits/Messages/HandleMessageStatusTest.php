<?php

namespace The42dx\Whatsapp\Tests\Integration\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Entities\Message\StatusEntity;
use The42dx\Whatsapp\Enums\MessageStatus;
use The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleMessageMetadata;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Tests\Integration\IntegrationTestCase;

class HandleMessageMetadataTest extends IntegrationTestCase {
    use HandleMessageMetadata;

    public static function msgStatusDataset(): array {
        return [
            MessageStatus::DELETED->value . ' status' => [MessageStatus::DELETED, 'deleted_at'],
            MessageStatus::DELIVERED->value . ' status' => [MessageStatus::DELIVERED, 'delivered_at'],
            MessageStatus::READ->value . ' status' => [MessageStatus::READ, 'read_at'],
            MessageStatus::SENT->value . ' status' => [MessageStatus::SENT, 'sent_at'],
        ];
    }

    #[DataProvider('msgStatusDataset')]
    public function test__handle_status__it_updates_message_status_and_relevant_timestamp(MessageStatus $status, string $timestampCol): void {
        $this->assertDatabaseCount('whatsapp_messages', 0);
        $message = WhatsappMessage::factory()->withStatus($status)->create();

        $this->assertDatabaseCount('whatsapp_messages', 1);

        $statusEntity = new StatusEntity(['id' => $message->whatsapp_message_id, 'status' => $status->value]);
        $this->handleStatus($statusEntity);

        $updated = WhatsappMessage::find($message->id);
        $this->assertEquals($status->value, $updated->status);
        $this->assertNotNull($updated->{$timestampCol});
        $this->assertDatabaseCount('whatsapp_messages', 1);
    }

    public function test__handle_status__it_does_nothing_if_message_not_found(): void {
        Log::spy();
        $this->assertDatabaseCount('whatsapp_messages', 0);

        $statusEntity = new StatusEntity(['id' => 'non_existing_id', 'status' => MessageStatus::SENT->value]);
        $this->handleStatus($statusEntity);

        Log::shouldReceive()
            ->once()
            ->with('Message not found on the database: ' . $statusEntity->id);

        $this->assertDatabaseCount('whatsapp_messages', 0);
    }
}
