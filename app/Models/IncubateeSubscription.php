<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncubateeSubscriptionDetail;
class IncubateeSubscription extends Model
{
    use HasFactory;
    protected $guarded  = [];
    public function incubatee_details(){
        return $this->hasMany(IncubateeSubscriptionDetail::class, 'incubatee_id');
    }
}
