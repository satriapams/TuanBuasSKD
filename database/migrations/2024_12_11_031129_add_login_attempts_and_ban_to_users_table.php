<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('login_attempts')->default(0); // Menyimpan jumlah percobaan login
            $table->boolean('is_banned')->default(false);  // Status banned
            $table->timestamp('banned_until')->nullable(); // Batas waktu banned
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_attempts', 'is_banned', 'banned_until']);
        });
    }

};
