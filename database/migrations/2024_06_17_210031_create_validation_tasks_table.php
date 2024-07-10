<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ValidationStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('validation_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('validationable_type')->comment('module to validate');
            $table->unsignedBigInteger('validationable_id')->comment('module object to validate');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->enum('status', ValidationStatus::values())->default(ValidationStatus::PENDING->value);
            $table->date('validated_at')->nullable();
            $table->unsignedBigInteger('who_validated')->nullable();
            $table->text('notes')->nullable();
            $table->text('validation_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_tasks');
    }
};
