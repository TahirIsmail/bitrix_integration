<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncubateeSubscriptionDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function incubatee(){
        return $this->belongsTo(IncubateeSubscription::class, 'incubatee_id');
    }

    public function voucher()
    {
        return $this->hasOne(PaymentDetails::class,'registration_id','registration_no')->orderBy('id', 'DESC');

    }

}
