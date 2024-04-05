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
        Schema::create('user_metas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->bigInteger("initial_staked_plan_id");
            $table->decimal('total_capping', $precision = 18, $scale = 8)->default(0.00);
            $table->decimal('remain_capping', $precision = 18, $scale = 8)->default(0.00);
            $table->integer('total_plans')->default(0);
            $table->integer('total_plans_active')->default(0);
            $table->integer('total_plans_expired')->default(0);
            $table->decimal('total_roi_income', $precision = 18, $scale = 8)->default(0);
            $table->decimal('total_direct_income', $precision = 18, $scale = 8)->default(0);
            $table->decimal('total_matching_income', $precision = 18, $scale = 8)->default(0);
            $table->decimal('total_reward_income', $precision = 18, $scale = 8)->default(0);
            $table->decimal('left_carry_forward', $precision = 18, $scale = 8)->default(0);
            $table->decimal('right_carry_forward', $precision = 18, $scale = 8)->default(0);
            $table->decimal('minimum_package', $precision = 18, $scale = 8)->default(0);
            $table->date('direct_expiry_at')->nullable();
            $table->tinyInteger('is_5_direct')->comment('1 = yes, 0 = no')->default(0);
            $table->tinyInteger('is_10_direct')->comment('1 = yes, 0 = no')->default(0);
            $table->tinyInteger('eligible_4x')->comment('1 = yes, 0 = no')->default(0);
            $table->tinyInteger('is_plan_active')->comment('1 = yes, 0 = no')->default(0);
            $table->decimal('reward_last_achieved', $precision = 18, $scale = 8)->default(0);
            $table->decimal('reward_income_gain', $precision = 10, $scale = 2)->default(0);
            $table->decimal('reward_times_left', $precision = 10, $scale = 2)->default(0);
            $table->date('reward_last_income')->nullable();
            $table->date('reward_next_income')->nullable();
            $table->date('reward_expiry_income')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_metas');
    }
};
