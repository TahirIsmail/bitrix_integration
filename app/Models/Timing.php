<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timing extends Model
{
    use HasFactory;
    protected $table = 'incubator_timings_tbl';
    public function shift() {
        return $this->belongsTo(Shift::class,'shift_id');
    }

    public function charges() {
        return $this->hasMany(Charge::class,'incubator_charges_id');
    }
}
