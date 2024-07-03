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
            $table->string('label')->length(50)->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->length(50)->nullable();
            $table->string('model')->length(50)->nullable();
            $table->string('class')->nullable();
            $table->smallInteger('order')->nullable(false)->default(1);
            $table->string('parent')->nullable();
            $table->json('fields')->nullable();
            $table->json('permissions')->nullable();

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
