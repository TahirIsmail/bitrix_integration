<?php

namespace App\Http\Controllers\Admin;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Helpers\Helper;
use App\Services\BitrixCallsService;
use App\Models\IncubateeSubscription;
use Illuminate\Support\Facades\Validator;
use App\Models\IncubateeSubscriptionDetail;

class IncubatorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->bitrix = new BitrixCallsService();
    }

    public function searchIncubatee(Request $request)
    {
        return view('admin.incubator.get_voucher');
    }

    public function searchIncubateeData(Request $request)
    {
        $data = IncubateeSubscription::with('incubatee_details')->where('email',$request->email)->first();
        return view('admin.incubator.get_voucher',['data'=>$data]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function updateDipositAmount(Request $request)
    {
        if ($request->id != '' && $request->value != '') {
            $payment = IncubateeSubscriptionDetail::where('id', $request->id)->orderBy('id', 'DESC')->first();
            $old_deposit_amount = ((!empty($payment->totalAmount)) ? $payment->totalAmount : '0');
            $payment->update(['totalAmount' => $request->value]);
            $new_deposit_amount = @$payment->totalAmount;

            if($payment->b24_lead_id || $payment->b24_deal_id){
               $bitrixObj=[
                'ID' => $payment->b24_deal_id??$payment->b24_lead_id,
                 'FIELDS[OPPORTUNITY]' => $payment->totalAmount,
                ];
                $queryFields = http_build_query($bitrixObj);
                $this->bitrix->sendCurlRequest($queryFields,'update',(($payment->b24_deal_id)?'crm.deal':'crm.lead'));
            return response()->JSON(['msg' => 'succsess']);
        } else {
            return response()->JSON(['msg' => 'error']);
        }
      }
    }

    public function voucherRecreate(Request $request)
    {
        $registration = IncubateeSubscriptionDetail::where('id',$request->id)->first();
        $getResponse = Helper::generateIncubatorInvoice($registration);
        if($getResponse['response'] == 'success'){
            $inoviceLink = $getResponse['invoice'];
        }
        $data1=[
            'ID' => $registration->b24_deal_id??$registration->b24_lead_id,
            'FIELDS[UF_CRM_1711712382]' => $inoviceLink, // Payment Link
            'FIELDS[UF_CRM_1707731587]' => '1st Installment', // Payment Link
        ];
        $queryFields   = http_build_query($data1);
        $ret =  $this->bitrix->sendCurlRequest($queryFields,'update',(($registration->b24_deal_id)?'crm.deal':'crm.lead'));
    }

    public function deleteIncubatee(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:incubatee_subscriptions,id',
        ],['id.exists'=>'Wrong Id entered, detail not exist']);
        if ($validator->fails()) {
            return redirect('admin/search-incubatee')->withErrors(['errors'=>$validator->errors()->first()]);
        }
        IncubateeSubscriptionDetail::where('incubatee_id', $request->id)->forceDelete();
        IncubateeSubscription::where('id', $request->id)->delete();
        return redirect('admin/incubator/search')->with(['message'=>'Incubatee Deleted Successfully']);
    }

    public function deleteIncubateeSubscription(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:incubatee_subscription_details,id',
        ],['id.exists'=>'Wrong Id entered, detail not exist']);
        if ($validator->fails()) {
            return redirect('admin/search-incubatee')->withErrors(['errors'=>$validator->errors()->first()]);
        }

        IncubateeSubscriptionDetail::where('id', $request->id)->delete();
        return redirect('admin/incubator/search')->with(['message'=>'Deleted Successfully']);
    }

}
