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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('menus')->comment('To identify menus')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->integer('order_number')->nullable();
            $table->boolean('position_type')->comment('1 for Header, 2 for Footer')->nullable();
            $table->boolean('status')->comment('1 Active, 2 for Deactive')->nullable();
            $table->string('icon_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
