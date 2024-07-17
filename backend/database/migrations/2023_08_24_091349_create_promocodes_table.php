<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  public function up(): void {
    Schema::create('promocodes', function (Blueprint $table) {
      $table->id();
      $table->timestamps();

      $table->string('code');
      $table->integer('discount');
      $table->timestamp('expires_at')->nullable();
      // timestamp with settings
      // $table->timestamp('expires_at', 0)->nullable();
    });
  }

  public function down(): void {
    Schema::dropIfExists('promocodes');
  }
};
