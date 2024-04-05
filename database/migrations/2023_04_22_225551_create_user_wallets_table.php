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
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("currency",5); // TRX
            $table->decimal('balance', $precision = 18, $scale = 8)->default(0); // 166.125
            $table->decimal('freeze_balance', $precision = 18, $scale = 8)->default(0); // 166.125
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};
