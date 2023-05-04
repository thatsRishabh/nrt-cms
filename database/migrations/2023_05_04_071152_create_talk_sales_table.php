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
        Schema::create('talk_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->dateTime('call_schedule_date_time')->nullable();
            $table->bigInteger('mobile_number')->nullable();
            $table->boolean('query_about')->comment('1 Sales, 2 for Reseller, 3 for Technical')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talk_sales');
    }
};
