<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncubateeSubscriptionDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function incubatee(){
        return $this->belongsTo(IncubateeSubscription::class, 'incubatee_id');
    }
}
