<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use The42dx\Whatsapp\Enums\MessageStatus;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Enums\MessageWay;

return new class extends Migration {
    const TABLE_NAME = 'whatsapp_msgs';

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create(config('whatsapp.database.table_name', self::TABLE_NAME), function (Blueprint $table) {
            $table->id();

            $table->string('whatsapp_message_id')
                  ->unique();
            $table->string('api_phone_number')
                  ->index();
            $table->string('contact_phone_number')
                  ->index();
            $table->foreignId('user_id')
                  ->nullable()
                  ->references(config('whatsapp.database.users_table_pk', 'id'))
                  ->on(config('whatsapp.database.users_table', 'users'))
                  ->onDelete('cascade');
            $table->enum('way', [
                MessageWay::INBOUND->value,
                MessageWay::OUTBOUND->value,
            ])->index();
            $table->enum('status', [
                MessageStatus::DELETED->value,
                MessageStatus::DELIVERED->value,
                MessageStatus::FAILED->value,
                MessageStatus::PENDING->value,
                MessageStatus::READ->value,
                MessageStatus::SENT->value,
                MessageStatus::WARNING->value,
            ])->default(MessageStatus::PENDING->value)->index();

            $table->enum('type', [
                MessageType::AUDIO->value,
                MessageType::CONTACTS->value,
                MessageType::DOCUMENT->value,
                MessageType::IMAGE->value,
                MessageType::INTERACTIVE->value,
                MessageType::LOCATION->value,
                MessageType::REACTION->value,
                MessageType::STICKER->value,
                MessageType::TEMPLATE->value,
                MessageType::TEXT->value,
                MessageType::UNSUPPORTED->value,
                MessageType::VIDEO->value,
            ])->index();

            $table->text('text')
                  ->nullable();

            $table->timestamps();
            $table->dateTime('deleted_at')
                  ->nullable();
            $table->dateTime('delivered_at')
                  ->nullable();
            $table->dateTime('read_at')
                  ->nullable();
            $table->dateTime('sent_at')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists(config('whatsapp.database.table_name', self::TABLE_NAME));
    }
};
