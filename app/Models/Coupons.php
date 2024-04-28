<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\IncubateeSubscriptionDetail;
class Coupons extends Model
{
    protected $table = 'coupons';

    /**
     * @return BelongsTo
     */

    public function user()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    static function CouponExists($code)
    {
        $coupon = Coupons::where(['code'=>$code])->first();
        if (Carbon::parse($coupon->expires_at)->toDateString() < Carbon::now()->toDateString()) {
          return response()->json(['success'=>'false','message'=>'Coupon Expired']);
        }
        else if(IncubateeSubscriptionDetail::where('coupon',$code)->exists()) {
            return response()->json(['success'=>'false','message'=>'Coupon already used.']);
        }
        else{
        return response()->json(['success'=>'true','data'=>$coupon->discount_amount]);
        }

    }

    static function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 12;

        $code = '';

        while (strlen($code) < 12) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (Coupons::where('code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }
}
