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
        Schema::create('transfer_funds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->decimal('amount', $precision = 18, $scale = 8)->comment("(in $)");
            $table->string('currency', 3)->default("AFC");
            $table->date("transfer_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_funds');
    }
};
