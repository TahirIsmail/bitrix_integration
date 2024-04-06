<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\City;
use App\Models\Lead;
use App\Models\Timing;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
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
        //
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
