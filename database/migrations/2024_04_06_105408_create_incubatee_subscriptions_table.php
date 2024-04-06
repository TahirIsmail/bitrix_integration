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
            $table->string('incubator_city')->nullable();
            $table->string('timing')->nullable();
            $table->string('shift')->nullable();
            $table->string('subscription_period')->nullable();
            $table->decimal('totalAmount', 8, 2)->nullable();
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
                'incubator_city',
                'timing',
                'shift',
                'subscription_period',
                'totalAmount',
            ]);
        });
        Schema::dropIfExists('incubatee_subscriptions');
    }
};
