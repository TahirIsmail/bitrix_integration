<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\b24leads;
use App\Models\PaymentDetails;
use App\Models\b24leadsInvoices;
use App\Models\IncubateeSubscriptionDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\BitrixCallsService;

class PaymentController extends Controller
{

    protected $bitrixCall;

    public function __construct()
    {
        $this->bitrixCall = new BitrixCallsService();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        Log::channel('bitrix')->info('==================Get Invoice=============== ' . Date('Y-m-d H:i:s'));
        Log::channel('bitrix')->debug($id);
        $invoice = b24leadsInvoices::with('b24lead')->where('id',$id)->first();
        Log::channel('bitrix')->debug($invoice);
        if (empty($invoice) OR $invoice->is_paid == 1) {
            return view('payments.transaction_expired');
        }

        $id = env('EC_PAY_APP_ID');
        $key = env('EC_PAY_KEY');
        $cipher = "aes-128-gcm";
        $iv = env('EC_PAY_IV');
        $iv = hex2bin($iv);

        // $getewayOptions = (($invoice->b24lead->booking_slot === 'Ask Moiz')?["stripeup"]:["stripem"]); //"kuickpay" stripup for USA, stripem for malaysia,
        $invoice_total_amount = $invoice->amount;
        $txn_desc = $invoice->b24lead->product_title.' Payment';

        $invoice_txn_currency = "PKR";
        $expiry_date = date('Y-m-d', strtotime("+15 days"));

        $data = array(
            "txn_amount" => $invoice_total_amount,
            "txn_customer_id" => $invoice->lead_id,
            "txn_customer_name" => $invoice->b24lead->name,
            "txn_customer_email" => $invoice->b24lead->email,
            "txn_customer_mobile" => $invoice->b24lead->phone,
            "txn_gateway_options" => ["kuickpay"],
            "txn_expiry_datetime" => $expiry_date,
            "txn_payment_type" => $txn_desc,
            "txn_customer_bill_order_id" => $invoice->order_id,
            "txn_description" => $txn_desc." - " .$invoice->b24lead->name,
            "txn_currency" => $invoice_txn_currency,
            "customer_ip" => $request->ip(),
            "txn_platform_return_url" => url("transaction-complete"),
        );
        Log::channel('bitrix')->info('==================Invoice Data=============== ' . Date('Y-m-d H:i:s'));
        Log::channel('bitrix')->debug($data);
        $plaintext = http_build_query($data);

        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
            $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
        }

        return view('payments.payment', ["id" => $id, "data" => $ciphertext]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payment_thankyou(Request $request)
    {
        Log::channel('bitrix')->info('==================Invoice Paid Thank you page=============== ' . Date('Y-m-d H:i:s'));
        Log::channel('bitrix')->debug($request->all());
        $get_invoice = b24leadsInvoices::firstWhere('order_id',$request->orderid);
        // gateway paid
        if ($request->status == 'completed' && isset($get_invoice) && $get_invoice->status != 1) {
            $get_invoice->is_paid = 1;
            $get_invoice->payment_date = date('Y-m-d H:i:s');
            $get_invoice->b24lead->status = 'approved';
            $get_invoice->push();

            $response = Http::get(env('BITRIX_URL').'crm.lead.update.json', [
                    'ID' => $get_invoice->b24lead->b24_lead_id,
                    'FIELDS[STATUS_ID]' => 'UC_VHVLEM',
                    'FIELDS[UF_CRM_1675176428307]' => '1st Installment Plan'
                ]);
                // $user = new User();
                // $user->subject = "Confirmation of payment";
                // $user->greeting = "Dear  " . ucfirst($get_invoice->b24lead->name) . ",";
                // $user->status = "Your payment has been processed and we thank you for your payment.";
                // $user->comment = new HtmlString("
                //     Feel free if you have any queries or need assistance, please email us on info@skillsrator.com.pk. Our customer support representative will immediately assist you.");
                // $user->email = $get_invoice->b24lead->email;
                // $user->bcc = 'sales@skillsrator.com.pk';
                // $user->notify(new IncubatorStatusesEmail());
        }
        return view('payments.thankyou');
    }

    public function transactionComptele(Request $request)
    {
        Log::channel('bitrix')->info('==================Invoice Paid Thank you page=============== ' . Date('Y-m-d H:i:s'));
        Log::channel('bitrix')->debug($request->all());

        if (isset($request->status)) {
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => env('PAYMENT_RECHECK_URL') . app('request')->input('orderid'),
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_HTTPHEADER => array(
            //         'Authorization: Bearer ' . env('SR_PAY_API_TOKEN')
            //     ),
            // ));
            // $response = curl_exec($curl);
            // curl_close($curl);

            // $data = json_decode($response)->data;
            $data = $request->all();

            if (!empty($data)) {
                $payment = b24leadsInvoices::where('order_id', $data['orderid'])->first();
                // Log::info('=========Incubator Payment Data==========='.$payment);
                if (isset($payment)) {
                    Log::info('=========Incubator Payment Data==========='.$payment->is_paid);
                        if ($payment->is_paid != 1) {
                            $payment->update(['is_paid' => '1','payment_date'=>now()]);
                            $payment->b24lead->update(['status' => 'approved']);
                            if ($payment->b24lead->b24_lead_id != '' || $payment->b24lead->b24_deal_id != '') {
                              if($payment->b24lead->b24_deal_id != null){
                                $b24_id = $payment->b24lead->b24_deal_id;
                                $b24_stage_id = 'C14:FINAL_INVOICE';
                                $b24_action = 'crm.deal';
                                $field = 'FIELDS[STAGE_ID]';
                              }else{
                                $b24_id = $payment->b24lead->b24_lead_id;
                                $b24_stage_id = 'UC_VHVLEM';
                                $b24_action = 'crm.lead';
                                $field = 'FIELDS[STATUS_ID]';
                              }

                               $bitrixObj=[
                                'ID' => $b24_id,
                                 $field => $b24_stage_id, // Payment Verification
                                 'FIELDS[UF_CRM_1680780128579]' => 'Validate',
                                ];
                                $queryFields = http_build_query($bitrixObj);
                                $this->bitrixCall->sendCurlRequest($queryFields,'update',$b24_action);
                            }
                        }
                        return response()->json(['status' => 200]);

                } else {
                    return response()->json(['status' => 400]);
                }
            } else {
                return response()->json(['status' => 400]);
            }
        }
    }

    public function IncubatorTransactionComptele(Request $request)
    {
        Log::channel('bitrix')->info('==================Incubator Invoice Paid Thank you page=============== ' . Date('Y-m-d H:i:s'));
        Log::channel('bitrix')->debug($request->all());

        if (isset($request->status)) {
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => env('PAYMENT_RECHECK_URL') . app('request')->input('orderid'),
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_HTTPHEADER => array(
            //         'Authorization: Bearer ' . env('SR_PAY_API_TOKEN')
            //     ),
            // ));
            // $response = curl_exec($curl);
            // curl_close($curl);

            // $data = json_decode($response)->data;
            $data = $request->all();

            if (!empty($data)) {
                $payment = PaymentDetails::where('order_id', $data['orderid'])->orWhere('registration_id',$data['orderid'])->first();
                // Log::info('=========Incubator Payment Data==========='.$payment);
                if (isset($payment)) {
                    Log::info('=========Incubator Payment Data==========='.$payment->is_paid);
                        if ($payment->is_paid != 1) {
                            $payment->update(['is_paid' => '1','payment_date'=>now()]);
                            $incReg = IncubateeSubscriptionDetail::where('registration_no', $payment->registration_id)->first();
                            $incReg->update(['status' => 'approved']);
                            if ($incReg->b24_lead_id != '' || $incReg->b24_deal_id != '') {
                              if($incReg->b24_deal_id != null){
                                $b24_id = $incReg->b24_deal_id;
                                $b24_stage_id = 'C14:FINAL_INVOICE';
                                $b24_action = 'crm.deal';
                                $field = 'FIELDS[STAGE_ID]';
                              }else{
                                $b24_id = $incReg->b24_lead_id;
                                $b24_stage_id = 'UC_VHVLEM';
                                $b24_action = 'crm.lead';
                                $field = 'FIELDS[STATUS_ID]';
                              }

                               $bitrixObj=[
                                'ID' => $b24_id,
                                 $field => $b24_stage_id, // Payment Verification
                                 'FIELDS[UF_CRM_1680780128579]' => 'Validate',
                                ];
                                $queryFields = http_build_query($bitrixObj);
                                $this->bitrixCall->sendCurlRequest($queryFields,'update',$b24_action);
                            }
                        }
                        return response()->json(['status' => 200]);

                } else {
                    return response()->json(['status' => 400]);
                }
            } else {
                return response()->json(['status' => 400]);
            }
        }
    }

    public function zeroTransactionComptele(Request $request)
    {
        $incompleteUrl = redirect(url('payment/incomplete'));
        if (isset($request->status)) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('PAYMENT_RECHECK_URL') . app('request')->input('orderid'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . env('SR_PAY_API_TOKEN')
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $data = json_decode($response)->data;
            if (empty($data)){
                return $incompleteUrl;
            }
            $payment = b24leadsInvoices::where('order_id', $data->customer_bill_order_id)->first();
            if (isset($payment) && $payment->email != $data->customer_email) {
               return $incompleteUrl;
            }
            if ($data->status === 'completed') {
                $payment->update(['is_paid' => '1','payment_date'=>now()]);
                    return view('payments.thankyou');;
            }
            else {
                // return $incomplete;
            }
        }
    }

}
