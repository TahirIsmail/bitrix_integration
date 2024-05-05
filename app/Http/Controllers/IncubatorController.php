<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Charge;
use App\Models\City;
use App\Models\Lead;
use App\Models\Timing;
use App\Models\Shift;
use App\Models\Coupons;
use Illuminate\Support\Facades\DB;
use App\Models\IncubateeSubscription;
use App\Models\IncubateeSubscriptionDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Services\BitrixCallsService;
use App\Services\KuickpayService;
class IncubatorController extends Controller
{

    public function __construct()
    {
        $this->bitrix = new BitrixCallsService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for store renewal.
     */
    public function store_renewal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|exists:incubatee_subscriptions,email',
            'cnic_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'facebook_profile' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'incubator_city' => 'required|string|max:255',
            'timing' => 'required|string|max:255',
            'shift' => 'required|string|max:255',
            'subscription_period' => 'required|integer|max:12',
            'totalAmount' => 'required|integer|max:9999999',
        ],['email.exists'=>'User with entered email is not exist, please apply again https://bitrixlead.skillsrator.com/']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        try {
            $incubateeSubscription = IncubateeSubscription::where(['email' => $request->email])->first();
            $currentDate = date('Y-m-d');
            $city = City::where('name',$request->incubator_city)->first();
            $incubateeSubscriptionDetail = IncubateeSubscriptionDetail::create([
                'incubatee_code' => $incubateeSubscription->id + 1000,
                'incubatee_id' => $incubateeSubscription->id,
                'timings' => $request->timing,
                'shift' => $request->shift,
                'city_id' => $city->id,
                'purpose' => $request->purpose,
                'city' => $request->incubator_city,
                'timing' => $request->timing,
                'shift' => $request->shift,
                'subscription_period' => $request->subscription_period.' '.'months',
                'totalAmount' => $request->totalAmount,
                'registration_no'=>'INC-SUBS-'.$incubateeSubscription->id.'-'.time(),
            ]);

                // $getResponse = Helper::generateIncubatorInvoice($incubateeSubscriptionDetail);
                // if($getResponse['response'] == 'success'){
                //     $inoviceLink = $getResponse['invoice'];

                // }
            $dealId = $this->bitrix->createIncDeal($incubateeSubscriptionDetail,0,'Incubator-Only');
            $incubateeSubscriptionDetail->b24_deal_id = $dealId;
            $incubateeSubscriptionDetail->update();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:incubatee_subscriptions,email',
            'cnic_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'facebook_profile' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'incubator_city' => 'required|string|max:255',
            'timing' => 'required|string|max:255',
            'shift' => 'required|string|max:255',
            'subscription_period' => 'required|integer|max:12',
            'totalAmount' => 'required|integer|max:9999999',
        ],['email.unique'=>'User with entered email is already exist, please apply renewal https://bitrixlead.skillsrator.com/incubator/renewal']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        try {
            $incubateeSubscription = IncubateeSubscription::create([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'cnic_number' => $request->cnic_number,
                'whatsapp_number' => $request->whatsapp_number,
                'facebook_profile' => $request->facebook_profile,
                'gender' => $request->gender,

            ]);
            $currentDate = date('Y-m-d');
            $city = City::where('name',$request->incubator_city)->first();
            $incubateeSubscriptionDetail = IncubateeSubscriptionDetail::create([
                'incubatee_code' => $incubateeSubscription->id + 1000,
                'incubatee_id' => $incubateeSubscription->id,
                'timings' => $request->timing,
                'shift' => $request->shift,
                'city_id' => $city->id,
                'purpose' => $request->purpose,
                'city' => $request->incubator_city,
                'timing' => $request->timing,
                'shift' => $request->shift,
                'subscription_period' => $request->subscription_period.' '.'months',
                'coupon' => $request->coupon_code,
                'totalAmount' => $request->totalAmount,
                'registration_no'=>'INC-SUBS-'.$incubateeSubscription->id.'-'.time(),
            ]);

            $lead_id = $this->bitrix->createIncLead($incubateeSubscriptionDetail,$request,207);
            $incubateeSubscriptionDetail->b24_lead_id = $lead_id;
            $incubateeSubscriptionDetail->update();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCoworkingSpace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cnic_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'facebook_profile' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'incubator_city' => 'required|string|max:255',
            'timing' => 'required|string|max:255',
            'shift' => 'required|string|max:255',
            'subscription_period' => 'required|integer|max:12',
            'totalAmount' => 'required|integer|max:9999999',
        ]
        // ,['email.unique'=>'User with entered email is already exist, please apply renewal https://bitrixlead.skillsrator.com/coworking/renewal']
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        try {
            $incubateeSubscription = IncubateeSubscription::updateOrCreate([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'cnic_number' => $request->cnic_number,
                'whatsapp_number' => $request->whatsapp_number,
                'facebook_profile' => $request->facebook_profile,
                'gender' => $request->gender,
            ],['email'=>$request->email]);
            $currentDate = date('Y-m-d');
            $city = City::where('name',$request->incubator_city)->first();
            $isExist = IncubateeSubscriptionDetail::where(['incubatee_id'=>$incubateeSubscription->id,'type'=>'coworking'])->first();
            $incubateeSubscriptionDetail = IncubateeSubscriptionDetail::create([
                'incubatee_code' => $incubateeSubscription->id + 1000,
                'incubatee_id' => $incubateeSubscription->id,
                'timings' => $request->timing,
                'shift' => $request->shift,
                'city_id' => $city->id,
                'purpose' => $request->purpose,
                'city' => $request->incubator_city,
                'timing' => $request->timing,
                'shift' => $request->shift,
                'type' => 'coworking',
                'subscription_period' => $request->subscription_period.' '.'months',
                'coupon' => $request->coupon_code,
                'totalAmount' => $request->totalAmount,
                'registration_no'=>'COWORKINGSPACE-SUBS-'.$incubateeSubscription->id.'-'.time(),
            ]);

            if(!empty($isExist)){
                $dealId = $this->bitrix->createIncDeal($incubateeSubscriptionDetail,0,'Co-Working Space');
                $incubateeSubscriptionDetail->b24_deal_id = $dealId;
                $incubateeSubscriptionDetail->update();
            }else{
                $lead_id = $this->bitrix->createIncLead($incubateeSubscriptionDetail,$request,1233);
                $incubateeSubscriptionDetail->b24_lead_id = $lead_id;
                $incubateeSubscriptionDetail->update();
            }


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function calculateSubscription(Request $request){

        $data = $request->all();
        $gender = $data['gender'];
        $couponCode = $data['coupon'];
        $city  = City::with('shifts.timings')->where('name',$data['incubator_city'])->first();
        $shift = $city->shifts->where('name',$data['shift'])->first();
        $timing = Timing::where('shift_id',$shift->id)->first();
        $charge = Charge::where('incubator_timings_id',$timing->id)->first();

        $subscription_months = $data['subscription_period'];
        switch ($subscription_months) {
            case 1:
                // Handle subscription period 1
                $totalAmount = $city->one_month_price;

                break;
            case 2:
                // Handle subscription period 2
                $totalAmount = $city->two_months_price;

                break;
            case 3:
                // Handle subscription period 3
                $totalAmount = $city->three_months_price;
                break;
            case 4:
                // Handle subscription period 4
                $totalAmount = $city->four_months_price;
                break;
            case 5:
                // Handle subscription period 5
                $totalAmount = $city->five_months_price;
                break;
            case 6:
                // Handle subscription period 6
                $totalAmount = $city->six_months_price;
                break;
            case 7:
                // Handle subscription period 7 (special case after 6 months)
                $totalAmount = (int)$city->one_month_price * 7;
                $offpercent = (int)$totalAmount * 0.4;
                $totalAmount = (int)$totalAmount - $offpercent;
                break;
            default:
                // Handle default case if subscription period is not within 1 to 7
                break;
        }
        //Lahore Karachi Islamabad-Rawalpindi
        if($gender == 'female' && ($city->name == 'Islamabad-Rawalpindi' || $city->name == 'Karachi' || $city->name == 'Lahore')){
            $offpercent = (int)$totalAmount * 0.4;
            $totalAmount = (int)$totalAmount - $offpercent;

        }
        elseif($gender == 'female' && $city->name == 'Multan'){
            $offpercent = (int)$totalAmount * 0.3;
            $totalAmount = (int)$totalAmount - $offpercent;
        }
        $couponAmount = '';
        if (isset($couponCode)) {
            $couponAmount = Coupons::where('code',$couponCode)->first();
            $couponAmount = $couponAmount->discount_amount;
            $totalAmount = $totalAmount - $couponAmount;
        }


        return view('layouts.partials.subscription_rows',compact('city','shift','timing','charge','totalAmount','subscription_months','couponCode','couponAmount'))->render();
    }

    public function calculateCoworkingSubscription(Request $request){

        $data = $request->all();
        $gender = $data['gender'];
        $couponCode = $data['coupon'];
        $city  = City::with('shifts.timings')->where('name',$data['incubator_city'])->first();
        $shift = $city->shifts->where('name',$data['shift'])->first();
        $timing = Timing::where('shift_id',$shift->id)->first();
        $charge = Charge::where('incubator_timings_id',$timing->id)->first();

        $subscription_months = $data['subscription_period'];
        switch ($subscription_months) {
            case 1:
                // Handle subscription period 1
                $totalAmount = $city->coworking_charges*1;

                break;
            case 2:
                // Handle subscription period 2
                $totalAmount = $city->coworking_charges*2;

                break;
            case 3:
                // Handle subscription period 3
                $totalAmount = $city->coworking_charges*3;
                break;
            case 4:
                // Handle subscription period 4
                $totalAmount = $city->coworking_charges*4;
                break;
            case 5:
                // Handle subscription period 5
                $totalAmount = $city->coworking_charges*5;
                break;
            case 6:
                // Handle subscription period 6
                $totalAmount = $city->coworking_charges*6;
                break;
            case 7:
                // Handle subscription period 7 (special case after 6 months)
                $totalAmount = (int)$city->coworking_charges* 7;
                $offpercent = (int)$totalAmount * 0.4;
                $totalAmount = (int)$totalAmount - $offpercent;
                break;
            default:
                // Handle default case if subscription period is not within 1 to 7
            break;
        }
        //Lahore Karachi Islamabad-Rawalpindi
        // if($gender == 'female' && ($city->name == 'Islamabad-Rawalpindi' || $city->name == 'Karachi' || $city->name == 'Lahore')){
        //     $offpercent = (int)$totalAmount * 0.4;
        //     $totalAmount = (int)$totalAmount - $offpercent;

        // }
        // elseif($gender == 'female' && $city->name == 'Multan'){
        //     $offpercent = (int)$totalAmount * 0.3;
        //     $totalAmount = (int)$totalAmount - $offpercent;
        // }
        $couponAmount = '';
        if (isset($couponCode)) {
            $couponAmount = Coupons::where('code',$couponCode)->first();
            $couponAmount = $couponAmount->discount_amount;
            $totalAmount = $totalAmount - $couponAmount;
        }


        return view('layouts.partials.subscription_rows',compact('city','shift','timing','charge','totalAmount','subscription_months','couponCode','couponAmount'))->render();
    }

    public function showSummary(Request $request){

        $data = $request->all();

        return view('layouts.partials.incubator_summary',compact('data'))->render();
    }

    public function couponDetails(Request $request)
    {
        if (isset(request()->coupon)) {
            request()->validate(['coupon' => 'exists:coupons,code']);
            return Coupons::CouponExists(request()->coupon);
        } else {
            return response()->json(['succss' => 'false', 'message' => 'Coupon Code Required']);
        }
    }

}
