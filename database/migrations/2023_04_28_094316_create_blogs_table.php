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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('content')->nullable();
            $table->string('video_path')->nullable();
            $table->string('image_path')->nullable();
            $table->string('posted_by')->nullable();
            $table->string('views')->nullable();
            $table->date('post_date')->nullable();
            $table->integer('order_number')->nullable();
            $table->boolean('status')->comment('1 Active, 2 for Deactive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
