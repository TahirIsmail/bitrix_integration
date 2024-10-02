<?php

namespace App\Http\Controllers\Admin;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\IncubateeSubscription;
use App\Models\IncubateeSubscriptionDetail;
use App\Models\DigitalIncubationRegistration;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function import_data(){
        $data = DB::select("SELECT incubator_backups.sID,incubator_backups.date,incubator_backups.Inc_Code,incubatee_subscriptions.id,incubator_backups.Office_City,incubator_backups.Payment_Mode,incubator_backups.Status,incubator_backups.Amount_Deposited,incubator_backups.Joining_Date,incubator_backups.Expiry_Date
        FROM incubatee_subscriptions INNER JOIN incubator_backups ON incubator_backups.Email = incubatee_subscriptions.email GROUP BY incubator_backups.sID");

        foreach ($data as $key => $value) {

            IncubateeSubscriptionDetail::updateOrCreate([
                'incubatee_code' => $value->Inc_Code,
                'incubatee_id' => $value->id,
                'created_at' => \Carbon\Carbon::parse($value->date),
                'timings' => 'NAN',
                'shift' => 'NAN',
                'city_id' => $this->getCityId($value->Office_City),
                'city' => $value->Office_City,
                'timing' => 'NAN',
                'shift' => 'NAN',
                'subscription_period' => $value->Payment_Mode,
                'totalAmount' => $value->Amount_Deposited,
                'registration_no'=>'NAN',
            ],['incubatee_code'=>$value->Inc_Code]);

        }
        exit;
    }

    public function searchData(Request $request)
    {
        $data = DigitalIncubationRegistration::where('email',$request->email)->first();
        return view('admin.search_candidate',['data'=>$data]);
    }

    public function getCityId($name){
        echo'<pre>';
        print_r($name);
        $city = City::where('name',$name)->pluck('id');
        return $city[0];
    }
}
