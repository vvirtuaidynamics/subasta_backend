<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tablename = config('forms.field_form_tablename', 'field_form');
        Schema::create($tablename, function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Field::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(App\Models\Form::class)->constrained()->onDelete('cascade');
            $table->json('options')->nullable();
            $table->string('rules')->nullable();
            $table->unsignedInteger('step')->default(0);
            $table->unsignedInteger('group')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_form');
    }
};
