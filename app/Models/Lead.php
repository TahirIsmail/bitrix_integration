<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'cnic_number', 'whatsapp_number', 'facebook_profile',
        'gender', 'incubator_city', 'preferred_timing', 'subscription_period', 'coupon_code'
    ];
}
