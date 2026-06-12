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
        Schema::table('products', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('price');
            $table->string('warranty')->nullable()->after('duration');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('total_price');
            $table->string('warranty')->nullable()->after('duration');
            $table->text('account_details')->nullable()->after('warranty');
            $table->string('payment_method')->nullable()->after('account_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['duration', 'warranty', 'account_details', 'payment_method']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['duration', 'warranty']);
        });
    }
};
