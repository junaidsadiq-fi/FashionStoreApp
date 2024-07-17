<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  public function up(): void {
    Schema::create('slides', function (Blueprint $table) {
      $table->id();
      $table->timestamps();

      $table->string('image')->nullable();
      $table->string('button_text')->nullable();
      $table->string('title_line_1')->nullable();
      $table->string('title_line_2')->nullable();
    });
  }

  public function down(): void {
    Schema::dropIfExists('slides');
  }
};
