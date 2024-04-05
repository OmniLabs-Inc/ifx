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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('user_unique_id');
            $table->string('user_sponser_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('lrl'); // left refer link
            $table->string('rrl'); // right refer link
            $table->bigInteger('direct_sponser_id')->nullable();
            $table->string('role')->comment('user,admin')->default('user');
            $table->string('side', 5)->comment('left, right');
            $table->string('user_unique_address', 64);
            $table->tinyInteger('is_email_verified')->comment('1 = true, 0 = false')->default(0);
            $table->tinyInteger('plan_active')->comment('1 = true, 0 = false')->default(0);
            $table->date('plan_activate_at')->nullable();
            $table->tinyInteger('status')->comment('1 = active, 0 = inactive')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
