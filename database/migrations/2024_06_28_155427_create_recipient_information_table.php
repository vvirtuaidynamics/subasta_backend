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
        Schema::create('recipient_information', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_name');
            $table->string('recipient_last_name');
            $table->string('recipient_company');
            $table->string('recipient_country');
            $table->string('recipient_postal_code');
            $table->string('recipient_city');
            $table->string('recipient_address');
            $table->string('recipient_address_2')->nullable();
            $table->string('recipient_address_3')->nullable();
            $table->integer('recipient_type_phone');
            $table->integer('recipient_country_code');
            $table->string('recipient_phone');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipient_information');
    }
};
