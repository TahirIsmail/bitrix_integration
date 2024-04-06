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
            $table->unsignedBigInteger('incubatee_id');
            $table->string('timings_or_shift');
            $table->unsignedBigInteger('city_id');
            $table->string('purpose')->nullable();
            

            $table->foreign('incubatee_id')->references('id')->on('incubatee_subscriptions');
            $table->foreign('city_id')->references('id')->on('incubator_cities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incubatee_subscription_details');
    }
};
