<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $guarded = ['id'];
    protected $table = 'cities';

    public function events(){
    	return $this->hasMany(Event::class);
    }
}
