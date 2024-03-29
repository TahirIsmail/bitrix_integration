<?php

namespace App\Http\Controllers\Bitrix_Hooks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BitrixCallsService;
use App\OneToOneConsultations;
use App\OnetoOneConsultationInvoices;
use App\Product;

class BitrixConsultationHooksController extends Controller
{
    protected $bitrixCall;

    public function __construct()
    {
        $this->bitrixCall = new BitrixCallsService();
    }

    public function createBitrixInvoice(Request $request)
    {
        if ($request['auth']['domain'] != 'extremecommerce.bitrix24.com' OR $request['program'] != 'CONSULTATION')
        {
          return response()->json(['status'=>200,'message'=>'something went wrong']);
        }
        $leadID = $request['lead_id'];
        $data = OneToOneConsultations::where('b24_lead_id',$leadID)->first();
        $data->status = 'selected';

        if ($data->status == 'selected') {
          $data->update();
          $invoice = OnetoOneConsultationInvoices::create([
              'consultation_id' => $data->id,
              'invoice_no' => 1,
              'amount' => $data->amount,
              'order_id' => '1-1-Consultation-' . $data->id . '-' . time(),
            ]);
          $inoviceLink = env('WEB_URL') . 'oneto-one-consultation/payment/' . $invoice->id;

          $data1=[
                  'ID' => $leadID,
                  'FIELDS[UF_CRM_1664642224317]' => $inoviceLink, // Payment Link
                  'FIELDS[UF_CRM_1675176428307]' => '1st Installment', // Payment Link
              ];
              $queryData1   = http_build_query($data1);
             $this->bitrixCall->sendCurlRequest($queryData1,"update","crm.lead");
        }else{
          return response()->json(['status'=>200,'message'=>'something went wrong']);
        }

    }
}
