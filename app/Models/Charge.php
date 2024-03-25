<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;
    protected $table = 'incubator_charges_tbl';
    public function timing() {
        return $this->belongsTo(Timing::class,'incubator_timings_id');
    }
}
