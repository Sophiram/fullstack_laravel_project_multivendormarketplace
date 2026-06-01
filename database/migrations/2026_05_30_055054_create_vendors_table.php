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
    Schema::create('vendors', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // ភ្ជាប់ទៅតារាង users
        $table->decimal('commission_rate', 5, 2)->default(10.00); // ភាគរយ Commission
        $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending'); // ស្ថានភាពអនុម័ត
        $table->text('bank_account_info')->nullable(); // ព័ត៌មានធនាគារ
        $table->timestamps();

        // កំណត់ទំនាក់ទំនង (Foreign Key)
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
