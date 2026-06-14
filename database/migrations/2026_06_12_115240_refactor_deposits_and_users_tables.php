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
        Schema::dropIfExists('deposit_transactions');

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 15, 2)->default(0)->after('password');
        });

        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->string('payment_method')->nullable()->after('user_id');
            $table->string('provider')->nullable()->after('payment_method');
            $table->decimal('amount', 15, 2)->default(0)->after('provider');
            $table->decimal('admin_fee', 15, 2)->default(0)->after('amount');
            $table->decimal('balance_received', 15, 2)->default(0)->after('admin_fee');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('balance_received');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'provider', 'amount', 'admin_fee', 'balance_received', 'status']);
            $table->decimal('balance', 15, 2)->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('balance');
        });

        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
};
