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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('The name of the form is computed by the name of the model and name of action. Ej. user_create, user_register');
            $table->string('label')->nullable()->comment('The name of the form is computed by the name of the model and name of action. Ej. user_create, user_register');
            $table->foreignIdFor(\App\Models\Module::class);
            $table->string('module')->nullable();
            $table->string('position')->nullable();
            $table->string('route')->nullable()->comment('The route to action of the controller to process the form');
            $table->json('options')->nullable();
            $table->json('default_value')->nullable();
            $table->string('class')->nullable()->comment('Para personalizar campos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_forms');
    }

};
