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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('id');
        });

        // Generate format SLRIZ0001 untuk pesanan yang sudah ada di database
        \Illuminate\Support\Facades\DB::statement("UPDATE orders SET order_number = CONCAT('SLRIZ', LPAD(id, 4, '0'))");

        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
