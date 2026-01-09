<?php

namespace The42dx\Whatsapp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use The42dx\Whatsapp\Enums\{MessageStatus, MessageType, MessageWay};
use The42dx\Whatsapp\Models\WhatsappMessage;

/**
 * WhatsappMessageFactory
 *
 * Factory for creating WhatsappMessage model instances for testing and seeding.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\The42dx\Whatsapp\Models\WhatsappMessage>
 */
class WhatsappMessageFactory extends Factory {
    protected $model = WhatsappMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'whatsapp_message_id' => 'wamid.' . Str::toBase64(Str::random(50)),
            'contact_phone_number' => $this->faker->phoneNumber(),
            'way' => $this->faker->randomElement([MessageWay::INBOUND, MessageWay::OUTBOUND]),
            'status' => MessageStatus::DELIVERED,
            'type' => MessageType::TEXT,
            'delivered_at' => now(),
            'sent_at' => now(),
        ];
    }

    /**
     * withStatus
     *
     * Indicate that the model's status is a specific status.
     */
    public function withStatus(MessageStatus $status): static {
        return $this->state(fn (): array => ['status' => $status]);
    }
}
