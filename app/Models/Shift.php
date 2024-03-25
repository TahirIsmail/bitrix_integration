<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $table = 'shifts';
    public function city() {
        return $this->belongsTo(City::class,'incubator_city_id');
    }

    public function timings() {
        return $this->hasMany(Timing::class,'shift_id');
    }

    
}
