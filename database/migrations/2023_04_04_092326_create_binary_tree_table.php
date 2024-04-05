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
        Schema::create('binary_tree', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('side',5)->comment('left, right')->nullable();
            $table->string('team',10)->comment('left_team, right_team')->nullable();
            $table->nestedSet();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binary_tree');
    }
};
