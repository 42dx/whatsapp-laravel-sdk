<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const USERS_TABLE  = 'users';
    const PHONE_COLUMN = 'phone';
    const USERS_PK = 'id';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table(config('whatsapp.database.users_table', self::USERS_TABLE), function (Blueprint $table) {
            $table->string(config('whatsapp.database.user_phone_column', self::PHONE_COLUMN))
                  ->unique()
                  ->nullable()
                  ->after(config('whatsapp.database.users_table_pk', self::USERS_PK));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table(config('whatsapp.database.users_table', self::USERS_TABLE), function (Blueprint $table) {
            $table->dropUnique([config('whatsapp.database.user_phone_column', self::PHONE_COLUMN)]);
            $table->dropColumn(config('whatsapp.database.user_phone_column', self::PHONE_COLUMN));
        });
    }
};
