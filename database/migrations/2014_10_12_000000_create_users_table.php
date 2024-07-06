<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->index();
            $table->string('username', length: 50)->unique();
            $table->string('name', length: 50)->nullable();
            $table->string('surname', length: 50)->nullable();
            $table->string('email', length: 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('active', false, true)->default(1);
            $table->text('avatar')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
