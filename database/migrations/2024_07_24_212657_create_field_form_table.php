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
        Schema::create('field_form', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id');
            $table->unsignedBigInteger('form_id');
            $table->json('options')->nullable();
            $table->text('rules')->nullable();
            $table->unsignedBigInteger('step')->default(0);
            $table->unsignedBigInteger('group')->default(0);
            $table->unsignedBigInteger('order')->default(0);
            $table->foreign('field_id')->references('id')->on('fields');
            $table->foreign('form_id')->references('id')->on('forms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_form');
    }
};
