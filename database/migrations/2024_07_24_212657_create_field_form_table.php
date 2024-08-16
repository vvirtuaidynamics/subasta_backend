<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('field_form', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Field::class, 'field_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(App\Models\Form::class, 'form_id')->constrained()->onDelete('cascade');
            $table->json('options')->nullable();
            $table->string('rules')->nullable();
            $table->unsignedInteger('step')->default(0);
            $table->unsignedInteger('panel')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_form');
    }
};
