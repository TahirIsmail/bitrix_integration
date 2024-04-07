<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetails extends Model
{
    // use SoftDeletes;
    protected $table = 'payments';
    protected $guarded = [''];

    public function incubatee(){
        return $this->belongsTo(IncubateeSubscription::class, 'user_id');
    }
}
