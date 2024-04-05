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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->date('roi_last_updated_date')->nullable();
            $table->date('matching_last_updated_date')->nullable();
            $table->date('reward_last_updated_date')->nullable();
            $table->decimal('alpha_price', $precision = 18, $scale = 8)->default(0); // 166.125
            $table->decimal('withdraw_fee', $precision = 18, $scale = 8)->default(0); // 166.125
            $table->tinyInteger('maintenance')->default(0)->comment('0 = off , 1 = on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
