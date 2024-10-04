<?php

namespace App\Http\Controllers\Bitrix_Hooks;

use Log;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\b24leads;
use App\Models\b24leadsInvoices;
use App\Models\IncubateeSubscriptionDetail;
use App\Models\DigitalIncubationRegistration;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Services\BitrixCallsService;

class BitrixHooksController extends Controller
{
    protected $bitrixCall;

    public function __construct()
    {
        $this->bitrixCall = new BitrixCallsService();
    }

    public function createBitrixInvoice(Request $request)
    {
        try {
         Log::channel('bitrix')->info('==================Bitrix Invoice Create=============== ' . Date('Y-m-d H:i:s'));
         Log::channel('bitrix')->debug(request()->all());

         if (isset($request['auth']) AND $request['auth']['domain'] == 'ice.bitrix24.com') {
            $inoviceLink = null;
            $leadID = $request['lead_id'];
            if($request['program'] == 'Incubator' OR $request['program'] == 'Co-Working Space'){
                $registration = IncubateeSubscriptionDetail::where('b24_lead_id',$leadID)->first();
                $getResponse = Helper::generateIncubatorInvoice($registration);
                if($getResponse['response'] == 'success'){
                    $inoviceLink = $getResponse['invoice'];
                }
                Log::channel('bitrix')->debug(['payment'=>$inoviceLink]);
            } elseif($request['program'] == 'Digital Incubation' OR $request['program'] == 'Digital Incubation Plus Community' OR $request['program'] == 'Community'){
                $registration = DigitalIncubationRegistration::where('b24_lead_id',$leadID)->first();
                $invoice = PaymentDetails::Create(
                    ['user_id' => $registration->id,
                     'reference_no' => '',
                     'title' => $request['program'].' Subscription',
                     'ip_address' => '',
                     'order_id' => $registration->registration_no,
                     'amount' => $registration->amount,
                     'type' => 'DINC',
                     'is_paid' => '0',
                     'registration_id' => $registration->registration_no,
                     'name' => $registration->name,
                     'email' => $registration->email,
                     'mobile' => $registration->whatsapp_number,
                     'currency' => 'PKR'
                    ]);
                $inoviceLink = env('APP_URL') . 'payment/' . $invoice->id;
                Log::channel('bitrix')->debug(['payment'=>$inoviceLink]);
            }

            elseif($request['program'] == 'Trainings') {

                $getData = $this->bitrixCall->sendCurlRequest(['ID' => $leadID],'get','crm.lead');
                Log::channel('bitrix')->debug($getData);
                $result = $getData['result'];
                $leadProducts = $this->bitrixCall->sendCurlRequest(['ID' => $leadID],'get','crm.lead.productrows');
                $product_result = $leadProducts['result'];

                $price = 0;
                $quantity = 0;
                $product_name = '';
                if (isset($product_result)) {
                    $product_name = $product_result[0]['PRODUCT_NAME'];
                    $price = $product_result[0]['PRICE'];
                }
                $product_name = Str::slug($product_name);
                Log::channel('bitrix')->debug($product_name.' ===> '.$price);
                $data = b24leads::create([
                    'name'=>$result['NAME'],
                    'email'=>$result['EMAIL'][0]['VALUE'],
                    'phone'=>$result['PHONE'][0]['VALUE'],
                    'program_title'=>$request['program'],
                    'product_title'=>$product_name,
                    'amount'=>$result['OPPORTUNITY'],
                    'status'=>'selected',
                    'message'=>$product_name.' Payment',
                    'b24_lead_id'=>$leadID,
                ]);

                $invoice = b24leadsInvoices::create([
                  'lead_id' => $data->id,
                  'invoice_no' => 1,
                  'amount' => $data->amount,
                  'order_id' => $request['program'].'-'.$product_name. '-' . $data->id . '-' . time(),
                ]);

                $inoviceLink = env('APP_URL') . 'trainings/payment/' . $invoice->id;
            }
            $data1=[
                'ID' => $leadID,
                'FIELDS[UF_CRM_1711712382]' => $inoviceLink, // Payment Link
                'FIELDS[UF_CRM_1707731587]' => '1st Installment',
            ];
              $queryData1   = http_build_query($data1);
              $ret =  $this->bitrixCall->sendCurlRequest($queryData1,"update","crm.lead");
              Log::channel('bitrix')->debug($ret);
         }
         return response()->json(['status'=>200,'success'=>true]);

        } catch (Exception $e) {
            Log::channel('bitrix')->info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    public function createBitrixDealInvoice(Request $request)
    {
        try {
         Log::channel('bitrix')->info('==================Bitrix Deal Invoice Create=============== ' . Date('Y-m-d H:i:s'));
         Log::channel('bitrix')->debug(request()->all());

         if (isset($request['auth']) AND $request['auth']['domain'] == 'ice.bitrix24.com') {
            $inoviceLink = null;
            $dealID = $request['deal_id'];
            if($request['program'] == 207 OR $request['program'] == 1233){
                //Incubator
                $registration = IncubateeSubscriptionDetail::where('b24_deal_id',$dealID)->first();
                $getResponse = Helper::generateIncubatorInvoice($registration);
                if($getResponse['response'] == 'success'){
                    $inoviceLink = $getResponse['invoice'];
                }
                Log::channel('bitrix')->debug(['payment'=>$inoviceLink]);
            }elseif($request['program'] == 1297){
                //Digital Incubator
                $registration = DigitalIncubationRegistration::where('b24_deal_id',$dealID)->first();
                $invoice = PaymentDetails::Create(
                    ['user_id' => $registration->id,
                     'reference_no' => '',
                     'title' => 'Digital Incubation Subscription',
                     'ip_address' => '',
                     'order_id' => $registration->registration_no,
                     'amount' => $registration->amount,
                     'type' => 'DINC',
                     'is_paid' => '0',
                     'registration_id' => $registration->registration_no,
                     'name' => $registration->name,
                     'email' => $registration->email,
                     'mobile' => $registration->whatsapp_number,
                     'currency' => 'PKR'
                    ]);
                $inoviceLink = env('APP_URL') . 'payment/' . $invoice->id;
                Log::channel('bitrix')->debug(['payment'=>$inoviceLink]);
            }
            $data1=[
                'ID' => $dealID,
                'FIELDS[UF_CRM_66128DD331D76]' => $inoviceLink, // Payment Link
                'FIELDS[UF_CRM_65CDE15B5E754]' => '1st Installment', // Payment Link
            ];
              $queryData1   = http_build_query($data1);
              $ret =  $this->bitrixCall->sendCurlRequest($queryData1,"update","crm.deal");
              Log::channel('bitrix')->debug($ret);
         }
         return response()->json(['status'=>200,'success'=>true]);

        } catch (Exception $e) {
            Log::channel('bitrix')->info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    public function dealCreated(Request $request)
    {
     try {
         Log::channel('bitrix')->info('==================Bitrix Deal Created=============== ' . Date('Y-m-d H:i:s'));
         Log::channel('bitrix')->debug(request()->all());
         Log::channel('bitrix')->debug(request());

         if (isset($request['auth']) AND $request['auth']['domain'] == 'ice.bitrix24.com') {
            Log::channel('bitrix')->debug(['contact_id'=>$request['contact_id']]);
            $leadID = $request['lead_id'];

        if($request['program'] == 'Trainings') {
              $registration = b24leads::where([
                     'b24_lead_id' => $leadID
                 ])->first();
              $registration['b24_deal_id'] = $request['deal_id'];
              $registration->save();
          }
          elseif($request['program'] == 'Incubator' || $request['program'] == 'Incubation Online' || $request['program'] == 'Co-working Space'){
            $inc = IncubateeSubscriptionDetail::where('b24_lead_id',$leadID)->first();

            $inc->b24_deal_id = $request['deal_id'];
            $inc->b24_contact_id = $request['contact_id'];
            // $inc->incubator->status = 1;
            $inc->push();

            if(!empty($leadID)){
                $getData = $this->bitrixCall->sendCurlRequest(['ID' => $leadID],'get','crm.lead');
            $result = $getData['result'];
            $data1=[
                    'ID' => $request['deal_id'],
                    'FIELDS[CURRENCY_ID]' => 'PKR',
                    'FIELDS[UF_CRM_65CDE15B27F5D]' => Helper::incubatorCityBitrixId($inc->city_id),
                    // 'FIELDS[UF_CRM_1664375057]' => 28,
                    'FIELDS[OPPORTUNITY]' => $inc->totalAmount, // Payment Link
                  ];
            $queryData1   = http_build_query($data1);
            $this->bitrixCall->sendCurlRequest($queryData1,"update","crm.deal");

            $products = array(["PRODUCT_ID" => Helper::incubatorProductCityBitrixId($inc->city_id), "PRICE" => $inc->totalAmount, "QUANTITY" => 1]);

            $product['id']   = $request['deal_id'];
            $product['rows'] = $products;
            $this->bitrixCall->sendCurlRequest(http_build_query($product),'set','crm.deal.productrows');
            }

          }
         }
         return response()->json(['status'=>200,'success'=>true]);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

    public function incubationActivation(Request $request)
    {
        try {
             Log::channel('bitrix')->info('==================Bitrix Incubation Activation Started=============== ' . Date('Y-m-d H:i:s'));
             Log::channel('bitrix')->debug(request()->all());

             if (isset($request['auth']) AND $request['auth']['domain'] == 'ice.bitrix24.com') {
                $dealID = $request['deal_id'];
                $joining_date = Carbon::createFromFormat('d/m/Y', $request['joining_date'])->toDateString();
                $contract = ($request['contract'] == 'Yes')?1:0;
                $card_issue = ($request['card_issue'] == 'Yes')?1:0;
                $card_no = ($request['card_no'] == 'Yes')?1:0;
                $snap_form = ($request['snap_form'] == 'Yes')?1:0;
                $added_whatsapp = ($request['added_whatsapp'] == 'Yes')?1:0;
            if (($request['program'] == 'Incubator' OR $request['program'] == 'Incubation Online' OR $request['program'] == 'Co-working Space'))
            {
                  $registration = IncubateeSubscriptionDetail::where([
                         'b24_deal_id' => $dealID,
                     ])->first();
                  if ($registration->status == 'active') {
                    Log::channel('bitrix')->info('Incubation Already Activated');
                    return false;
                  }
                  $exp = IncubateeSubscriptionDetail::where('id', $registration->id)->latest('id')->skip(1)->first();
                  $expiry_date = null;
                  $getRemainingDays = 0;
                  if (isset($exp) && !empty($exp) && $exp->status == 'approved' && (now()->toDateString() < Carbon::parse($exp->expiry_date)->toDateString())) {
                        $data = date_diff(date_create($exp->expiry_date), date_create(now()->toDateString()));
                    $getRemainingDays = $data->days;
                  }
                  $mode = explode(' ',$registration->subscription_period);
                  $expiry_date = null;
                  $days = $getRemainingDays;
                  if (isset($mode[0]) and $mode[0] != '') {
                  $expiry_date = Carbon::parse($joining_date)->addMonthsNoOverflow($mode[0])->addDays($days)->toDateString();
                  }
                  $registration->expiry_date = $expiry_date;
                  $registration->joining_date = $joining_date;
                //   $registration->joining_status = 2;
                //   $registration->contract_addendum_signed = $contract;
                //   $registration->issued_card = $card_issue;
                //   $registration->card_no = $card_no;
                //   $registration->snap_form_filled = $snap_form;
                //   $registration->added_in_whatsApp_groups = $added_whatsapp;

                  $registration->push();

                    $data = [
                        'ID' => $dealID,
                        'FIELDS[CLOSEDATE]' => $expiry_date,
                        'FIELDS[UF_CRM_65CDE15B72DC4]' => $expiry_date,
                        'FIELDS[UF_CRM_1679135173]' => 'Activated',
                    ];
                    $res = $this->bitrixCall->sendCurlRequest(http_build_query($data),"update","crm.deal");

                    Log::channel('bitrix')->debug($data);
              }
             }
             return response()->json(['status'=>200,'success'=>true]);

            } catch (Exception $e) {
                Log::info($e->getMessage());
                throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
            }
    }

    public function programActivation(Request $request)
    {
        try {
             Log::channel('bitrix')->info('==================Bitrix Program Activation Started=============== ' . Date('Y-m-d H:i:s'));
             Log::channel('bitrix')->debug(request()->all());

             if (isset($request['auth']) AND $request['auth']['domain'] == 'ice.bitrix24.com') {
                $dealID = $request['deal_id'];
                $joining_date = Carbon::createFromFormat('d/m/Y', $request['BEGINDATE'])->toDateString();
                // $contract = ($request['contract'] == 'Yes')?1:0;
                // $card_issue = ($request['card_issue'] == 'Yes')?1:0;
                // $card_no = ($request['card_no'] == 'Yes')?1:0;
                // $snap_form = ($request['snap_form'] == 'Yes')?1:0;
                // $added_whatsapp = ($request['added_whatsapp'] == 'Yes')?1:0;
            if (($request['program'] == 'Digital Incubation' OR $request['program'] == 'Digital Incubation Plus Community' OR $request['program'] == 'Community'))
            {
                  $registration = DigitalIncubationRegistration::where([
                         'b24_deal_id' => $dealID,
                     ])->first();
                  if ($registration->status == 'activated') {
                    Log::channel('bitrix')->info($registration->program.' Already Activated');
                    return false;
                  }
                  $expiry_date = null;
                  $expiry_date = Carbon::parse($joining_date)->addMonthsNoOverflow(3)->toDateString();
                  $registration->expiry_date = $expiry_date;
                  $registration->joining_date = $joining_date;
                  $registration->status = 'activated';
                  $registration->push();

                    $data = [
                        'ID' => $dealID,
                        'FIELDS[CLOSEDATE]' => $expiry_date,
                        'FIELDS[UF_CRM_65CDE15B72DC4]' => $expiry_date,
                        'FIELDS[UF_CRM_1679135173]' => 'Activated',
                    ];
                    $res = $this->bitrixCall->sendCurlRequest(http_build_query($data),"update","crm.deal");

                    Log::channel('bitrix')->debug($data);
              }
             }
             return response()->json(['status'=>200,'success'=>true]);

            } catch (Exception $e) {
                Log::info($e->getMessage());
                throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
            }
    }

}
