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
        //shifts  //timings // charges //cities
        $data = $request->all();
        $city  = City::with('shifts.timings')->where('name',$data['incubator_city'])->first();
        $shift = $city->shifts()->where('name',$data['shift'])->first();
        $timing = Timing::where('shift_id',$shift->id)->first();
        $charge = Charge::where('incubator_timings_id',$timing->id)->first();

        
        return view('layouts.partials.subscription_rows',compact('city','shift','timing','charge'))->render();
    }
}
