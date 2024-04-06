<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\City;
use App\Models\Lead;
use App\Models\Timing;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use App\Models\IncubateeSubscription;
use App\Models\IncubateeSubscriptionDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
class IncubatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        ]);

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
                'totalAmount' => $request->totalAmount,
                'joining_date' => $currentDate,
                'expiry_date' => date('Y-m-d', strtotime($currentDate . ' + 30 days')),
            ]);

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
        $city  = City::with('shifts.timings')->where('name',$data['incubator_city'])->first();
        $shift = $city->shifts()->where('name',$data['shift'])->first();
        $timing = Timing::where('shift_id',$shift->id)->first();
        $charge = Charge::where('incubator_timings_id',$timing->id)->first();
        
        $subscription_months = $data['subscription_period'];
        if($data['subscription_period'] == 6){
            $totalAmount = ((int)($charge->amount)  * (int)($data['subscription_period']));
            $OffAmount = (int)($totalAmount) * (float)(0.2);
            $totalAmount = (int)($totalAmount - $OffAmount);

        }
        elseif($data['subscription_period'] == 3){
            $totalAmount = ((int)($charge->amount)  * (int)($data['subscription_period']));
            $OffAmount = (int)($totalAmount) * (float)(0.1);
            $totalAmount = (int)($totalAmount - $OffAmount);

        }
        else{
            $totalAmount = (int)($charge->amount)  * (int)($data['subscription_period']);
        }
        if($gender == 'male' && $shift->name == 'Night'){
           
            $OffAmount = (int)($totalAmount) * (float)(0.5);
            $totalAmount = (int)($totalAmount - $OffAmount);


        }
        if($gender == 'female' && ($shift->name == 'Morning' || $shift->name == 'Evening')){
            
            $OffAmount = (int)($totalAmount) * (float)(0.6);
            $totalAmount = (int)($totalAmount - $OffAmount);
        }
        
        return view('layouts.partials.subscription_rows',compact('city','shift','timing','charge','totalAmount','subscription_months'))->render();
    }

    public function showSummary(Request $request){
        
        $data = $request->all();
        return view('layouts.partials.incubator_summary',compact('data'))->render();
    }
}
