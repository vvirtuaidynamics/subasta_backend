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
    Schema::create('document_carriers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('carrier_id')->constrained();
      $table->string('path');
      $table->string('name');
      $table->string('type');
      $table->string('size');
      $table->string('document');
      $table->date('validated')->nullable();
      $table->date('expire_date')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('document_carriers');
  }
};
