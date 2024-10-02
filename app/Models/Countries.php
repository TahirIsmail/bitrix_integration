<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'countries';

    public function ecProgramRegistrationsKyc()
    {
        return $this->belongsTo('App\Models\ec_programs_registration_kyc', 'id');
    }
}
