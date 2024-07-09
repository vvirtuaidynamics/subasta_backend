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
        Schema::create('sender_information', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name');
            $table->string('sender_last_name');
            $table->string('sender_company');
            $table->string('sender_country');
            $table->string('sender_postal_code');
            $table->string('sender_city');
            $table->string('sender_address');
            $table->string('sender_address_2')->nullable();
            $table->string('sender_address_3')->nullable();
            $table->integer('sender_type_phone');
            $table->integer('sender_country_code');
            $table->string('sender_phone');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sender_information');
    }
};
