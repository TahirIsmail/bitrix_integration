<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'incubator_cities';
    public function shifts() {
        return $this->hasMany(Shift::class,'incubator_city_id');
    }
}
