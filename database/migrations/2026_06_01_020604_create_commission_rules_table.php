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
        Schema::create('commission_rules', function (Blueprint $table) {
            // 💡 បង្កើត commission_id ជា Primary Key (INT 11)
            $table->id('commission_id');

            // 💡 បង្កើត category_id ជា Foreign Key ភ្ជាប់ទៅកាន់ table categories
            // សូមប្រាកដថា table categories ត្រូវបានបង្កើតមុន table មួយនេះ
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade');

            // 💡 ភាគរយ Commission (DECIMAL 5,2)
            $table->decimal('commission_rate', 5, 2);

            // 💡 ស្ថានភាព Active / Inactive (VARCHAR 20)
            $table->string('status', 20)->default('Active');

            // 💡 បង្កើត created_at និង updated_at (TIMESTAMP)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};
