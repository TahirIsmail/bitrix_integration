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
        Schema::create('incubatee_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email')->nullable();
            $table->string('cnic_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('facebook_profile')->nullable();
            $table->string('gender')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incubatee_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'user_name',
                'email',
                'cnic_number',
                'whatsapp_number',
                'facebook_profile',
                'gender',
            ]);
        });
        Schema::dropIfExists('incubatee_subscriptions');
    }
};
