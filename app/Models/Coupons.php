<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\IncubateeSubscriptionDetail;
use App\Models\DigitalIncubationRegistration;
class Coupons extends Model
{
    protected $table = 'coupons';
    protected $casts = [
        'code' => 'string',
        'type' => 'string',
    ];
    /**
     * @return BelongsTo
     */

    public function user()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    static function CouponExists($request)
    {
        $coupon = Coupons::where(['code'=>$request->coupon,'type'=>str_replace('-',' ',$request->type)])->first();
        if(!isset($coupon)){
            return response()->json(['success'=>'false','message'=>'Wrong Coupon Code.']);
        } else if (isset($coupon) AND Carbon::parse($coupon->expires_at)->toDateString() < Carbon::now()->toDateString()) {
          return response()->json(['success'=>'false','message'=>'Coupon Expired']);
        } else if($request->type == 'incubation' AND IncubateeSubscriptionDetail::where('coupon',$request->coupon)->exists()) {
            return response()->json(['success'=>'false','message'=>'Coupon already used.']);
        } else if($request->type == 'digital-incubation' AND DigitalIncubationRegistration::where('coupon',$request->coupon)->exists()){
            return response()->json(['success'=>'false','message'=>'Coupon already used.']);
        } else{
            $msg = '';
            if($request->type == 'digital-incubation'){
            $msg = '<div class="text-success">
                        <h6 class="my-0">Coupon discount</h6>
                    </div>
                    <span class="text-success">'.$coupon->discount_amount.' PKR</span>';
            }
            return response()->json(['success'=>'true','data'=>$coupon->discount_amount,'msg'=>$msg]);
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
