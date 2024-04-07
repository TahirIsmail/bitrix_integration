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
            if($request['program'] == 'Incubator'){
                $registration = IncubateeSubscriptionDetail::where('b24_lead_id',$request['lead_id'])->first();
                $getResponse = Helper::generateIncubatorInvoice($registration);
                if($getResponse['response'] == 'success'){
                    $inoviceLink = $getResponse['invoice'];
                }
                Log::channel('bitrix')->debug(['payment'=>$inoviceLink]);
            }
            elseif($request['program'] == 'Trainings') {
                $leadID = $request['lead_id'];
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

}
