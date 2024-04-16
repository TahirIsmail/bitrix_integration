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
        Schema::table('incubator_cities', function (Blueprint $table) {
            //
            $table->decimal('one_month_price', 10, 2)->after('label')->nullable();
            $table->decimal('two_months_price', 10, 2)->after('one_month_price')->nullable();
            $table->decimal('three_months_price', 10, 2)->after('two_months_price')->nullable();
            $table->decimal('three_months_discount_percent', 5, 2)->after('three_months_price')->nullable();

            $table->decimal('four_months_price', 10, 2)->after('three_months_discount_percent')->nullable();
            $table->decimal('four_months_discount_percent', 5, 2)->after('four_months_price')->nullable();

            $table->decimal('five_months_price', 10, 2)->after('four_months_discount_percent')->nullable();
            $table->decimal('five_months_discount_percent', 5, 2)->after('five_months_price')->nullable();

            $table->decimal('six_months_price', 10, 2)->after('five_months_discount_percent')->nullable();
            $table->decimal('six_months_discount_percent', 5, 2)->after('six_months_price')->nullable();
            $table->decimal('additional_discount_female_percent', 5, 2)->after('six_months_discount_percent')->nullable();
            $table->decimal('additional_discount_night_shift_percent', 5, 2)->after('additional_discount_female_percent')->nullable();
            $table->decimal('additional_discount_after_six_months_percent', 5, 2)->after('additional_discount_night_shift_percent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incubator_cities', function (Blueprint $table) {
            $table->dropColumn(['one_month_price', 'two_months_price', 'three_months_price', 'four_months_price', 'five_months_price', 'six_months_price', 'three_months_discount_percent', 'four_months_discount_percent', 'five_months_discount_percent', 'six_months_discount_percent', 'additional_discount_female_percent', 'additional_discount_night_shift_percent', 'additional_discount_after_six_months_percent']);
        });
    }
};
