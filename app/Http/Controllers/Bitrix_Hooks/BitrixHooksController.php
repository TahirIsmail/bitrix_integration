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
            if($request['program'] == 'Incubator'){
                $registration = IncubateeSubscriptionDetail::where('b24_lead_id',$leadID)->first();
                $getResponse = Helper::generateIncubatorInvoice($registration);
                if($getResponse['response'] == 'success'){
                    $inoviceLink = $getResponse['invoice'];
                }
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

                $inoviceLink = env('APP_URL') . 'payment/' . $invoice->id;
            }
            $data1=[
                'ID' => $leadID,
                'FIELDS[UF_CRM_1711712382]' => $inoviceLink, // Payment Link
                'FIELDS[UF_CRM_1707731587]' => '1st Installment', // Payment Link
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

    public function dealCreated(Request $request)
    {
     try {
         Log::channel('bitrix')->info('==================Bitrix Deal Created=============== ' . Date('Y-m-d H:i:s'));
         Log::channel('bitrix')->debug(request()->all());
         Log::channel('bitrix')->debug(request());

         if (isset($request['auth']) AND $request['auth']['domain'] == 'ice.bitrix24.com') {
            Log::channel('bitrix')->debug(['contact_id'=>$request['contact_id']]);
            $leadID = $request['lead_id'];

        if($request['program'] == 'Managed Services') {
              $registration = b24leads::where([
                     'b24_lead_id' => $leadID
                 ])->first();
              $registration['b24_deal_id'] = $request['deal_id'];
              $registration->save();
          }
          elseif($request['program'] == 'Incubator' || $request['program'] == 'Incubation Online' || $request['program'] == 'Co-working Space'){
            $inc = IncubateeSubscriptionDetail::where('b24_lead_id',$leadID)->first();
            User::where('id',$inc->user_id)->update(['b24_contact_id'=>$request['contact_id']]);
            $inc->b24_deal_id = $request['deal_id'];
            // $inc->incubator->status = 1;
            $inc->push();

            // $getData = $this->bitrixCall->sendCurlRequest(['ID' => $leadID],'get','crm.lead');
            // $result = $getData['result'];
            // $data1=[
            //         'ID' => $request['deal_id'],
            //         'FIELDS[CURRENCY_ID]' => 'PKR',
            //         'FIELDS[UF_CRM_1664375027]' => $inc->city->bitrix_city_id,
            //         'FIELDS[UF_CRM_1664375057]' => 28,
            //         'FIELDS[OPPORTUNITY]' => $inc->amount_deposit, // Payment Link
            //       ];
            // $queryData1   = http_build_query($data1);
            // $this->bitrixCall->sendCurlRequest($queryData1,"update","crm.deal");

            // $products = array(["PRODUCT_ID" => $inc->city->bitrix_product_id, "PRICE" => $inc->amount_deposit, "QUANTITY" => 1]);

            // $product['id']   = $request['deal_id'];
            // $product['rows'] = $products;
            // $this->bitrixCall->sendCurlRequest(http_build_query($product),'set','crm.deal.productrows');
          }
         }
         return response()->json(['status'=>200,'success'=>true]);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new ApiOperationFailedException($e->getMessage(), $e->getCode());
        }
    }

}
