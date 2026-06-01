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
    Schema::table('stores', function (Blueprint $table) {
        if (!Schema::hasColumn('stores', 'vendor_id')) {
            $table->unsignedBigInteger('vendor_id')->after('id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        }

        if (!Schema::hasColumn('stores', 'logo')) {
            $table->string('logo', 255)->nullable()->after('details');
        }

        if (!Schema::hasColumn('stores', 'address')) {
            $table->text('address')->nullable()->after('logo');
        }

        if (!Schema::hasColumn('stores', 'store_email')) {
            $table->string('store_email')->nullable()->after('address');
        }

        if (!Schema::hasColumn('stores', 'store_phone')) {
            $table->string('store_phone')->nullable()->after('store_email');
        }

        if (!Schema::hasColumn('stores', 'status')) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('store_phone');
        }

        if (!Schema::hasColumn('stores', 'commission_rate')) {
            $table->decimal('commission_rate', 5, 2)->default(10.00)->after('status');
        }

        if (!Schema::hasColumn('stores', 'is_active')) {
            $table->boolean('is_active')->default(true)->after('commission_rate');
        }
    });
}

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn(['vendor_id', 'logo', 'address', 'store_email', 'store_phone', 'status', 'commission_rate', 'is_active']);
        });
    }
};
