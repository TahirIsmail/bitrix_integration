<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\b24leads;
use App\Models\b24leadsInvoices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $invoice = b24leadsInvoices::with('b24lead')->where('id',$id)->first();
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
            "txn_gateway_options" => 'kuickpay',
            "txn_expiry_datetime" => $expiry_date,
            "txn_payment_type" => $txn_desc,
            "txn_customer_bill_order_id" => $invoice->order_id,
            "txn_description" => $txn_desc." - " .$invoice->b24lead->name,
            "txn_currency" => $invoice_txn_currency,
            "customer_ip" => $request->ip(),
            "txn_platform_return_url" => url("payment/thankyou"),
        );
        // dd($data);
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
        $get_invoice = b24leadsInvoices::firstWhere('order_id',$request->orderid);

        // gateway paid
        if ($request->status == 'completed' && isset($get_invoice) && $get_invoice->status != 1) {
            $get_invoice->is_paid = 1;
            $get_invoice->payment_date = date('Y-m-d H:i:s');
            $get_invoice->b24lead->status = 'approved';
            $get_invoice->push();

            $response = Http::get(env('BITRIX_URL').'crm.lead.update.json', [
                    'ID' => $get_invoice->b24lead->b24_lead_id,
                    'FIELDS[STATUS_ID]' => 'UC_WK7W03',
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
            Log::info('===============Incubator Payment Debug Start================');

            if (!empty($data)) {
                $payment = PaymentDetails::where('order_id', $data->customer_bill_order_id)->orWhere('registration_id',$data->customer_bill_order_id)->first();
                Log::info('=========Incubator Payment Data==========='.$payment);
                if (isset($payment) && $payment->email == $data->customer_email) {
                    if ($data->status === 'completed') {
                        if ($payment->is_paid != 1) {
                            $incReg = IncubatorPaymentDetails::where('registration_no', $payment->registration_id)->first();

                            $city = $incReg->city_id ? $incReg->city->name : $incReg->city_name;
                            $user = User::find($incReg->user_id);
                            $user->greeting = "Dear " . $user->name . ",";
                            $user->email = $user->email;
                            $AppCount = IncubatorPaymentDetails::where(['user_id' => $request->id, 'status' => 4])->count();
                            if ($AppCount >= 1) {
                                $user->refund == 'no';
                                $user->notify(new ApprovedStatusEmail($city));
                            } else {
                                $user->refund == 'yes';
                                $user->notify(new ApprovedStatusEmail($city));
                            }
                            $payment->update(['is_paid' => '1']);
                            $incReg->incubator->update(['approve_at' => Carbon::now()->toDateString(), 'status' => '1']);
                            $incReg->update(['status' => '1', 'approve_at' => Carbon::now()->toDateString(), 'is_paid' => '1']);
                            $com = new IncubatorComment();
                            $com->comment = 'Payment Received';
                            $com->status = 'Approved & Paid';
                            $com->enroll_id = $incReg->incubator_id;
                            $com->user = 0;
                            $com->save();
                            Log::info('=========Incubator Payment Complete===========');
                            if ($incReg->b24_lead_id != '' || $incReg->b24_deal_id != '') {

                               if($incReg->b24_deal_id != null){
                                $b24_id = $incReg->b24_deal_id;
                                $b24_stage_id = 'C14:FINAL_INVOICE';
                                $b24_action = 'crm.deal';
                                $field = 'FIELDS[STAGE_ID]';
                               }else{
                                $b24_id = $incReg->b24_lead_id;
                                $b24_stage_id = 'UC_WK7W03';
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
                    }
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
            $payment = PaymentDetails::where('order_id', $data->customer_bill_order_id)->first();
            if (isset($payment) && $payment->email != $data->customer_email) {
               return $incompleteUrl;
            }
            if ($data->status === 'completed') {
                    $incReg = IncubatorPaymentDetails::where('registration_no', $data->customer_bill_order_id)->first();

                    $incReg->incubator->update(['status' => '0']);
                    $incReg->update(['status' => '0', 'is_paid' => '1']);
                    $com = new IncubatorComment();
                    $com->comment = 'Zero amount subscription';
                    $com->status = 'Pending';
                    $com->enroll_id = $incReg->incubator_id;
                    $com->user = 0;
                    $com->save();
                    return view('payments.thankyou');;
            }
            else {
                return $incomplete;
            }
        }
    }

}
