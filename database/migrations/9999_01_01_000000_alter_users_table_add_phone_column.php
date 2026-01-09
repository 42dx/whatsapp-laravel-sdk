<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table(config('whatsapp.database.users_table'), function (Blueprint $table): void {
            $table->string(config('whatsapp.database.messageable_phone_column'))
                ->unique()
                ->nullable()
                ->after(config('whatsapp.database.users_table_pk'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table(config('whatsapp.database.users_table'), function (Blueprint $table): void {
            $table->dropUnique([config('whatsapp.database.messageable_phone_column')]);
            $table->dropColumn(config('whatsapp.database.messageable_phone_column'));
        });
    }
};
