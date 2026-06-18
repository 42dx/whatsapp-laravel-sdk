<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table(config('whatsapp.database.users_table'), function(Blueprint $table): void {
            $table->string(config('whatsapp.database.messageable_phone_column'))
                ->unique()
                ->nullable()
                ->after(config('whatsapp.database.users_table_pk'));
            $table->timestamp(config('whatsapp.database.messageable_phone_column') . '_verified_at')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table(config('whatsapp.database.users_table'), function(Blueprint $table): void {
            $phoneColumn = config('whatsapp.database.messageable_phone_column');
            $phoneVerifiedAtColumn = "{$phoneColumn}_verified_at";

            $table->dropUnique([$phoneColumn]);
            $table->dropColumn([$phoneColumn, $phoneVerifiedAtColumn]);
        });
    }
};
