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
        Schema::create('tbl_digital_incubation_registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->enum('program',['digital incubator','digital incubator plus community','community only'])->default(NULL);
            $table->string('course_batch')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('coupon')->nullable();
            $table->string('registration_no')->nullable();
            $table->integer('b24_lead_id')->nullable();
            $table->integer('b24_deal_id')->nullable();
            $table->date('applied_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status',['pending','approved','activated','on-hold','withdrawn','completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
