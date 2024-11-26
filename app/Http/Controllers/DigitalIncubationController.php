<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Charge;
use App\Models\City;
use App\Models\Lead;
use App\Models\Timing;
use App\Models\Shift;
use App\Models\Coupons;
use App\Models\Cities;
use App\Models\Courses;
use App\Models\Countries;
use App\Rules\capchaRule;
use App\Rules\formValidation;
use Illuminate\Support\Facades\DB;
use App\Models\DigitalIncubationRegistration;
use App\Models\IncubateeSubscription;
use App\Models\IncubateeSubscriptionDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Services\BitrixCallsService;
use App\Services\KuickpayService;
use Illuminate\Support\Facades\Log;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class DigitalIncubationController extends Controller
{
    private $bitrix;
    public function __construct()
    {
        $this->bitirx = new BitrixCallsService();
    }

    /**
     * Display a form.
     */
    public function create()
    {
        $countries = Countries::get();
        $course_amount = 24000;
        return view('digital_incubation.form',['countries'=>$countries,'amount'=>$course_amount]);
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
            // 'g-recaptcha-response' => 'required|recaptchav3:submit,0.5',
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cnic_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'gender' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'course1' => 'required',
            'course2' => 'required',
            'course3' => 'required',
        ]
        // , [
        //     'g-recaptcha-response' => [
        //         'recaptchav3' => 'Captcha expired... Refresh your page',
        //     ],
        // ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        $user = User::where('email',$request->email)->first();
        if (empty($user)) {
        $user = User::create([
                'name' => $request->user_name,
                'email' => $request->email,
                'cnic_number' => $request->cnic_number,
                'whatsapp_number' => $request->whatsapp_number,
                'gender' => $request->gender,
                'country_id' => $request->country,
                'city_id' => $request->city
            ]);
        }

        // Check if candidate is already enrolled
        $enrolled = formValidation::checkAlreadyEnrolled($user->id);
        if (isset($enrolled) AND ($enrolled->program == 'digital incubator' OR $enrolled->program == 'digital incubator plus community') AND ($enrolled->status == 'pending' OR $enrolled->status == 'approved')) {
            return response()->json(['error' => 'You are already enrolled for Course.']);
        }

        switch (isset($enrolled) AND $enrolled->course_batch) {
            case '1st':
                $course = '2nd';
            break;
            case '2nd':
                $course = '3rd';
            break;
            case '3rd':
                $course = '1st';
            break;
            default:
                $course = '1st';
            break;
        }

        try {
            $digitalIncubationSubscription = DigitalIncubationRegistration::create([
                'user_id' => $user->id,
                'course_batch'=>$course,
                'program' => 'digital incubator',
                'course1' => (($request->course1 != 'Select Course')?$request->course1:''),
                'course2' => (($request->course2 != 'Select Course')?$request->course2:''),
                'course3' => (($request->course3 != 'Select Course')?$request->course3:''),
                'amount'=>$request->amount,
                'coupon'=>$request->coupon,
                'registration_no' => 'DINC-SUBS-'.rand(2,50).'-'.time(),
                'applied_date' => now()
            ]);

            if ($course != '1st') {
                $deal_id = $this->bitrix->createDigitalIncDeal($digitalIncubationSubscription,0,1297);
                $digitalIncubationSubscription->b24_deal_id = $deal_id;
            }else{
                $lead_id = $this->bitrix->createDigitalIncLead($digitalIncubationSubscription,$request,1297);
                $digitalIncubationSubscription->b24_lead_id = $lead_id;
            }
            $digitalIncubationSubscription->update();
            $msg = 'Thanks Mr./Mrs. '.ucfirst($request->user_name).' your information have been submitted
                                successfully for the future reference we will contact you soon.';
            return response()->json(['success' => true,'msg'=>$msg]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
            // return response()->json(['error' => 'Something went wrong, Please try again or contact with Skillsrator Team.']);
        }
    }

    public function getCourses(Request $request){
        $data = DigitalIncubationRegistration::whereHas('candidate', function($q) use ($request) {
            $q->where('email', '=', $request->id);
        })->latest()->first();
        if (isset($data) AND $data->course_batch == '2nd') {
            $batch = '3rd';
        } elseif (isset($data) AND $data->course_batch == '1st') {
            $batch = '2nd';
        } else{
            $batch = '1st';
        }
        $courses = Courses::where('type',$batch)->get();
        $html = '<option selected disabled>Select Course</option>';
        foreach ($courses as $key => $value) {
        $html .= '<option data-ch="'.$value->price.'" value="'.$value->id.'">'.$value->title.'</option>';
        }
        return response()->json(['status'=>200,'data'=>$html]);
    }

    public function getCitiesByCountryId(Request $request)
    {
        $cities = Cities::where('name','LIKE','%'.$request->input('term').'%')->where('country_id',$request->input('country_id'))->get(['id','name as text']);
        return response()->json(['results' => $cities]);
    }

}
