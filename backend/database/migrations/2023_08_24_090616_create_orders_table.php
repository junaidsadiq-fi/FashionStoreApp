<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  public function up(): void {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->timestamps();

      $table->string('email');
      $table->json('products');
      $table->string('address');
      $table->string('full_name');
      $table->string('phone_number');
      $table->string('order_status');
      $table->string('payment_status');
      $table->decimal('total_price', 8, 2);
      $table->decimal('subtotal_price', 8, 2);
      $table->decimal('discount', 8, 2)->nullable();
      $table->decimal('delivery_price', 8, 2)->nullable();
    });
  }

  public function down(): void {
    Schema::dropIfExists('orders');
  }
};
