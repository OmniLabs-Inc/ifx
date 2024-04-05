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
        Schema::create('matching_incomes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->date("week_start_date");
            $table->date("week_end_date");
            $table->decimal('matching_income', $precision = 18, $scale = 8)->comment("(in $)");
            $table->date("date");
            $table->string("status",20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_incomes');
    }
};
