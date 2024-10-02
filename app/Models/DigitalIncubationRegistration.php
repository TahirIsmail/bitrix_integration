<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Courses;
use App\Models\Cities;
use App\Models\Countries;

class DigitalIncubationRegistration extends Model
{
    use HasFactory;
    protected $table = 'tbl_digital_incubation_registrations';
    protected $guarded  = [];

    public function candidate(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course1Details(){
        return $this->belongsTo(Courses::class, 'course1');
    }

    public function course2Details(){
        return $this->belongsTo(Courses::class, 'course2');
    }

    public function course3Details(){
        return $this->belongsTo(Courses::class, 'course3');
    }
}
