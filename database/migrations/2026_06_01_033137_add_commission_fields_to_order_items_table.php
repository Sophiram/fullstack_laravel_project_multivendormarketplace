<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
            Schema::table('order_items', function (Blueprint $table) {
                // 🟢 បន្ថែមជួរឈរសម្រាប់រក្សាទុកទិន្នន័យគណនា Commission
                $table->unsignedBigInteger('vendor_id')->nullable()->after('product_id');
                $table->decimal('commission_rate', 5, 2)->default(0.00)->after('price'); // រក្សាទុក % (ឧទាហរណ៍៖ 10.00%)
                $table->decimal('commission_amount', 10, 2)->default(0.00)->after('commission_rate'); // ទឹកប្រាក់ដែលក្រុមហ៊ុនកាត់ទុក
                $table->decimal('vendor_net_amount', 10, 2)->default(0.00)->after('commission_amount'); // ទឹកប្រាក់សុទ្ធ Vendor ទទួលបាន
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // 🔴 ដកជួរឈរទាំងនេះចេញវិញ ប្រសិនបើមានការ Rollback
            $table->dropColumn(['vendor_id', 'commission_rate', 'commission_amount', 'vendor_net_amount']);
        });
    }
};
