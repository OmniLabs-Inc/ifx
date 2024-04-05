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
        Schema::create('roi_incomes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("staked_plan_id");
            $table->bigInteger("user_id");
            $table->decimal('roi_income', $precision = 18, $scale = 8)->comment("(in $)");
            $table->date("date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roi_incomes');
    }
};
