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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->length(50)->unique();
            $table->string('label')->length(50)->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->length(50)->nullable(false);
            $table->string('model_name')->length(50)->nullable(false);
            $table->string('model_namespace')->nullable(false);
            $table->json('fields')->nullable(false);
            $table->boolean('readonly')->nullable()->default(false);
            $table->smallInteger('order')->nullable(false)->default(1);
            $table->string('parent_name')->nullable();

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
