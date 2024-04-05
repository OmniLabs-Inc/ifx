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
        Schema::create('staked_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->decimal('stake_currency_amount', $precision = 18, $scale = 8);
            $table->string('stake_currency', 5);
            $table->decimal('stake_currency_price', $precision = 18, $scale = 8);
            $table->decimal('usdt_amount', $precision = 18, $scale = 8);
            $table->decimal('daily_roi_income', $precision = 18, $scale = 8);
            $table->date("plan_start_at");
            $table->string('is_admin_created');
            $table->string("status", 50)->comment("expired, opened");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staked_plans');
    }
};
