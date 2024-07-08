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
        Schema::create('bearings', function (Blueprint $table) {
            $table->id();
            $table->string('weight');
            $table->string('wide');
            $table->string('long');
            $table->string('height');
            $table->string('total_packages');
            $table->text('content_description');
            $table->string('declared_value');
            $table->string('service_type');
            $table->boolean('shipping_insurance');
            $table->boolean('weekend_delivery');
            $table->boolean('home_pickup');
            $table->string('observations');
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bearings');
    }
};
