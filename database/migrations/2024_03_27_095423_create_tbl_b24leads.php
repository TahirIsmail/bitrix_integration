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
        Schema::create('tbl_b24leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('program_title');
            $table->string('product_title');
            $table->unsignedBigInteger('amount');
            $table->enum('status',['pending','rejected','selected','approved','completed','withdrawn'])->default('pending');
            $table->text('message');
            $table->unsignedBigInteger('b24_lead_id');
            $table->unsignedBigInteger('b24_deal_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_b24leads');
    }
};
