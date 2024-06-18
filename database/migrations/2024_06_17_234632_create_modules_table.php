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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('label')->length(30)->nullable(false)->unique();
            $table->string('ico')->length(50)->nullable(false);
            $table->string('model_name')->length(50)->nullable(false);
            $table->string('model_namespace')->nullable(false);
            $table->boolean('readonly')->default(false);
            $table->smallInteger('order')->default(1);
            $table->bigInteger('application_id')->unsigned();
            $table->foreign('application_id')->references('id')->on('applications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};