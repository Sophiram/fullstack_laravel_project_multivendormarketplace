<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('shipping_company')->nullable(); // ឧទាហរណ៍: J&T, Kerry, Flash
            $table->string('tracking_number')->nullable();  // លេខតាមដានទំនិញ
            $table->string('shipping_status')->default('pending'); // Pending, Shipped, In-Transit, Delivered
            $table->decimal('shipping_cost', 10, 2)->default(0); // តម្លៃដឹកជញ្ជូន
            $table->timestamp('shipped_at')->nullable();    // ថ្ងៃដែលបានផ្ញើ
            $table->timestamp('delivered_at')->nullable();  // ថ្ងៃដែលអតិថិជនទទួលបាន
            $table->text('notes')->nullable();              // កំណត់សម្គាល់បន្ថែម
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
