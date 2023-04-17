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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->longText('description')->nullable();
            $table->string('logo_path')->default('https://www.nrtsms.com/images/no-image.png')->nullable();
            $table->string('logo_thumb_path')->default('https://www.nrtsms.com/images/no-image.png')->nullable();
            $table->string('fav_icon')->default('https://www.nrtsms.com/images/no-image.png')->nullable();
            $table->string('call_us')->nullable();
            $table->string('mail_us')->nullable();
            $table->time('service_start_time')->nullable();
            $table->time('service_end_time')->nullable();
            $table->string('fb_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('insta_url')->nullable();
            $table->string('linkedIn_url')->nullable();
            $table->string('pinterest_url')->nullable();
            $table->string('copyright_text')->nullable();
            $table->text('address')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('org_number')->nullable();
            $table->bigInteger('customer_care_number')->nullable();
            $table->string('allowed_app_version')->nullable();
            $table->string('invite_url')->nullable();
            $table->string('play_store_url')->nullable();
            $table->string('app_store_url')->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_contact_number')->nullable();
            $table->string('certificate_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
