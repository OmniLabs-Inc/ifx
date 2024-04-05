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
        Schema::create('matching_income_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->decimal('match_income', $precision = 18, $scale = 8)->comment("(in $)");
            $table->decimal('left_business', $precision = 18, $scale = 8)->comment("(in $)");
            $table->decimal('right_business', $precision = 18, $scale = 8)->comment("(in $)");
            $table->decimal('left_carry_forward', $precision = 18, $scale = 8)->comment("(in $)");
            $table->decimal('right_carry_forward', $precision = 18, $scale = 8)->comment("(in $)");
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_income_histories');
    }
};
