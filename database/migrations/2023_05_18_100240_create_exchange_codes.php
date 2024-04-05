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
        Schema::create('exchange_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->bigInteger("redeemed_by")->nullable();
            $table->string("code");
            $table->timestamp("expired_at");
            $table->decimal("amount", $precision = 18, $scale = 8)->default(0); // 166.125
            $table->string("currency");
            $table->boolean("is_redeemed")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_codes');
    }
};
