<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        $table->id();
//        $table->string('name');
//        $table->string('last_name')->nullable();
//        $table->string('email')->unique();
//        $table->timestamp('email_verified_at')->nullable();
//        $table->string('password');
//        $table->rememberToken();
//        $table->string('status')->default('pending');
//        $table->timestamp('last_login_at')->nullable();
//        $table->timestamp('deleted_at')->nullable();
//        $table->timestamps();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', length: 50)->index()->unique();
            $table->string('first_name', length: 50)->nullable();
            $table->string('last_name', length: 50)->nullable();
            $table->string('email', length: 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('token')->nullable();
            $table->rememberToken();
            $table->string('status', length: 25)->default('pending');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
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
