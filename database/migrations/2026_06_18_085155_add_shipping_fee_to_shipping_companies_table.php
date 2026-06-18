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
        // ពិនិត្យមើលថាតើមាន column ហ្នឹងហើយឬនៅ
        if (!Schema::hasColumn('shipping_companies', 'shipping_fee')) {
            Schema::table('shipping_companies', function (Blueprint $table) {
                $table->decimal('shipping_fee', 10, 2)->default(0)->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shipping_companies', 'shipping_fee')) {
            Schema::table('shipping_companies', function (Blueprint $table) {
                $table->dropColumn('shipping_fee');
            });
        }
    }
};
