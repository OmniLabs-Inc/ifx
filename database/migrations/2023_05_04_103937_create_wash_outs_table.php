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

        Schema::create('wash_outs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->decimal('matching_income', $precision = 18, $scale = 8)->comment("(in $)");
            $table->decimal('washout_income', $precision = 18, $scale = 8)->comment("(in $)");
            $table->decimal('current_remaing_capping', $precision = 18, $scale = 8)->comment("(in $)");
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
        Schema::dropIfExists('wash_outs');
    }
};
