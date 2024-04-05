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
        Schema::create('withdrawal', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->decimal('amount', $precision = 18, $scale = 8)->comment('in $');
            $table->decimal('alpha_price', $precision = 18, $scale = 8)->comment('in $');
            $table->decimal('withdraw_fees_percentage', $precision = 18, $scale = 8)->comment('in $');
            $table->decimal('total_fees', $precision = 18, $scale = 8)->comment('in $');
            $table->decimal('usdt_after_fees', $precision = 18, $scale = 8)->comment('in $');
            $table->decimal('alpha_qty', $precision = 18, $scale = 8);
            $table->string('to_address');
            $table->string('reason')->default('');
            $table->text('transaction_detail');
            $table->string("status", 20)->comment("pending, completed")->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal');
    }
};
