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
        Schema::create('incubatee_subscription_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incubatee_code');
            $table->unsignedBigInteger('incubatee_id')->unsigned()->default(0);
            $table->foreign('incubatee_id')->references('id')->on('incubatee_subscriptions');
            $table->string('timings');
            $table->string('shift');


            
            $table->unsignedBigInteger('city_id')->unsigned()->default(0);
            $table->foreign('city_id')->references('id')->on('incubator_cities');
            $table->string('purpose');
            $table->string('city');
            $table->string('timing');
            $table->string('subscription_period');
            $table->enum('status', ['pending', 'unpaid', 'approved', 'active', 'rejected', 'expired', 'refund'])->default('pending');
            $table->decimal('totalAmount', 10, 2);
            $table->text('registration_no')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incubatee_subscription_details', function (Blueprint $table) {
            $table->dropForeign(['incubatee_id']);
            $table->dropForeign(['city_id']);
        });

        Schema::dropIfExists('incubatee_subscription_details');
    }
};
