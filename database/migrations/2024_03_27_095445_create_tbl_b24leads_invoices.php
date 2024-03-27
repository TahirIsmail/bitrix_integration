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
        Schema::create('tbl_b24leads_invoices', function (Blueprint $table) {
            $table->id();
            $table->Integer('invoice_no');
            $table->unsignedBigInteger('amount');
            $table->string('order_id');
            $table->integer('lead_id');
            $table->tinyInteger('is_paid')->default(0);
            $table->datetime('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_b24leads_invoices');
    }
};
