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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->foreign('menu_id')->references('id')->on('menus')->comment('To identify menus')->onDelete('cascade');
            $table->unsignedBigInteger('sub_menu_id')->nullable();
            $table->foreign('sub_menu_id')->references('id')->on('menus')->comment('To identify menus')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('icon_image_path')->nullable();
            $table->string('redirect_url')->nullable();
            $table->integer('order_number')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->comment('1 for active , 2 for inactive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
