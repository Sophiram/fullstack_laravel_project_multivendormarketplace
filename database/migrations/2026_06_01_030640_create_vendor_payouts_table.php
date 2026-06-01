<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);              // ចំនួនទឹកប្រាក់ទូទាត់សរុប
            $table->decimal('commission_deducted', 10, 2); // ទឹកប្រាក់ដែលប្រព័ន្ធកាត់ជា Commission
            $table->decimal('net_amount', 10, 2);          // ទឹកប្រាក់សុទ្ធដែល Vendor ទទួលបាន
            $table->string('payment_method')->default('ABA'); // ABA, Wing, Acleda ...
            $table->text('bank_details_snapshot');         // កត់ត្រាព័ត៌មានគណនីធនាគារពេលដកប្រាក់
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('transaction_receipt')->nullable(); // រូបភាពចុងសន្លឹកផ្ទេរប្រាក់
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
    }
};
