<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable(); // ឧទាហរណ៍៖ Birthday, Wedding, New Year
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false); // សម្រាប់ដាក់បង្ហាញ Best Seller Badge
            $table->boolean('status')->default(true); // true = Active, false = Inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_collections');
    }
};
