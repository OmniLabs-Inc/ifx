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
        Schema::create('deposit_crypto_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("transaction_id",50);
            $table->string("deposit_currency",5); // TRX
            $table->decimal('deposit_currency_price', $precision = 18, $scale = 8); // 0.06645
            $table->decimal('deposit_currency_amount', $precision = 18, $scale = 8); // 500
            $table->decimal('usdt_amount', $precision = 18, $scale = 8); // 33.225
            $table->decimal('alpha_amount', $precision = 18, $scale = 8); // 166.125
            $table->decimal('alpha_price', $precision = 18, $scale = 8); // 0.20
            $table->string('hash', 255)->nullable(); // 1b15775fd473315b7ac1d1cbe3be5a8a0f7c6881f839746935fe999ea40f6ea1
            $table->string('chain_type', 10)->default('TRX');
            $table->string('token_address', 255)->nullable();
            $table->string('token_type', 255)->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('from_wallet_address', 255)->nullable();
            $table->string('to_wallet_address', 255)->nullable();
            $table->tinyInteger('status')->default(0)->comment("0 = pending, 1 = rejected, 2 = completed");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_crypto_logs');
    }
};
