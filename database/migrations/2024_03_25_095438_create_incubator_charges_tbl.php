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
        Schema::create('incubator_charges_tbl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incubator_timings_id');
            $table->foreign('incubator_timings_id')->references('id')->on('incubator_timings_tbl')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incubator_charges_tbl');
    }
};
