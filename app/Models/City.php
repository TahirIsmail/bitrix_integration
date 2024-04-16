<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'incubator_cities';
    protected $fillable = [
        'name',
        'label',
        'one_month_price',
        'two_months_price',
        'three_months_price',
        'four_months_price',
        'five_months_price',
        'six_months_price',
        'additional_discount_female',
        'additional_discount_night_shift',
        'additional_discount_after_six_months',
    ];
    public function getSubscriptionPrice(int $duration)
    {
        switch ($duration) {
            case 1:
                return $this->one_month;
            case 2:
                return $this->two_months;
            case 3:
                return $this->three_months;
            case 4:
                return $this->four_months;
            case 5:
                return $this->five_months;
            case 6:
                return $this->six_months;
            default:
                return 0;
        }
    }

    public function getAdditionalDiscount(string $discountType)
    {
        switch ($discountType) {
            case 'female':
                return $this->additional_discount_female;
            case 'night_shift':
                return $this->additional_discount_night_shift;
            case 'after_six_months':
                return $this->additional_discount_after_six_months;
            default:
                return 0;
        }
    }
    
    public function shifts() {
        return $this->hasMany(Shift::class,'incubator_city_id');
    }
}
