<?php

namespace App\Helpers;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentDetails;
class Helper
{

    static function incubatorCityBitrixId($cityId) {
        switch ($cityId) {
            case '6':
            $bitrixId = 557;
            break;
            case '7':
            $bitrixId = 555;
            break;
            case '8':
            $bitrixId = 553;
            break;
            case '9':
            $bitrixId = 551;
            break;
            case '10':
            $bitrixId = 559;
            break;
            case '11':
            $bitrixId = 561;
            break;
            default:
            $bitrixId = 553;
            break;
        }
        return $bitrixId;
    }

    static function incubatoSubscriptionTypeBitrixId($type)
        {
        switch ($type) {
            case '1 month':
            $bitrixId = 591;
            break;
            case '2 months':
            $bitrixId = 593;
            break;
            case '2 month':
            $bitrixId = 593;
            break;
            case '2 month - 10% off':
            $bitrixId = 595;
            break;
            case '3 months':
            $bitrixId = 597;
            break;
            case '3 months - 10% off':
            $bitrixId = 599;
            break;
            case '3 months - 20% off':
            $bitrixId = 601;
            break;
            case '4 months':
            $bitrixId = 603;
            break;
            case '4 months - 30% off':
            $bitrixId = 605;
            break;
            case '5 months':
            $bitrixId = 607;
            break;
            case '5 months - 40% off':
            $bitrixId = 609;
            break;
            case '6 months':
            $bitrixId = 611;
            break;
            case '6 months - 20% off':
            $bitrixId = 615;
            break;
            case '6 months - 45% off':
            $bitrixId = 613;
            break;
            default:
            $bitrixId = 591;
            break;
        }
        return $bitrixId;
    }

    static function generateIncubatorInvoice($incRegistration){
            $response['response'] = 'error';
            $details = $incRegistration;
            $auth_user = $incRegistration->incubatee;

                  $id = env('EC_PAY_APP_ID');
                  $key = env('EC_PAY_KEY');
                  $cipher = "aes-128-gcm";
                  $iv = env('EC_PAY_IV');
                  $iv = hex2bin($iv);
                  $data = array(
                      "txn_amount" => $details->totalAmount,
                      "txn_customer_id" => $auth_user->id, //customer id
                      "txn_customer_name" => $auth_user->user_name, //customer name
                      "txn_customer_email" => $auth_user->email, //customer email
                      "txn_customer_mobile" => $auth_user->whatsapp_number, // customer mobile
                      "txn_payment_type" => 'Incubation Subscription',
                      "txn_customer_bill_order_id" => 'INC-SUBS-' . $auth_user->id . '-' . time(),
                      "txn_description" => "Incubation Subscription - (" . ucfirst($auth_user->user_name) . " - " . $details->city . " - " . $details->subscription_period . " - " . $details->shift . ")",
                      "installments" => 1,
                      "txn_gateway_options" => ["kuickpay"],
                      "txn_currency" => "PKR",
                      "txn_currency_rate" => "Dollar_Rates_2",
                      "txn_expiry_datetime" => date('Y-m-d', strtotime("+30 days")),
                      "customer_ip" => request()->ip(), //"192.100.2.15",
                      "txn_platform_return_url" => url("transaction-complete"),
                  );

                  $plaintext = http_build_query($data);
                  if (in_array($cipher, openssl_get_cipher_methods())) {
                      $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
                      $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
                  }
                  $fieldsData = ['id' => $id, 'data' => $ciphertext];
                  $fieldsData = http_build_query($fieldsData);
                  $curl = curl_init();
                  curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://pay.skillsrator.com/api/kuickpay2/vouchers/create",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => $fieldsData,
                      CURLOPT_HTTPHEADER => array(
                          'Authorization: Bearer ' . env('EC_PAY_API_TOKEN'),
                          'Accept: application/json',
                          'Content-Type: application/x-www-form-urlencoded'
                      ),
                  ));
                  $response = curl_exec($curl);
                  curl_close($curl);
                  $voucherNo = '0';
                  $voucher_path = '';
                  $response = json_decode($response);
                  Log::channel('bitrix')->debug($response);
                  if (isset($response->vouchers)) {
                    $voucherNo = $response->vouchers[0];
                    }
                    PaymentDetails::Create(
                      ['user_id' => $auth_user->id, 'reference_no' => $voucherNo, 'title' => $data['txn_payment_type'], 'ip_address' => $data['customer_ip'], 'order_id' => $data['txn_customer_bill_order_id'], 'amount' => $data['txn_amount'], 'type' => 'INC', 'is_paid' => '0', 'registration_id' => $details->registration_no, 'name' => $data['txn_customer_name'], 'email' => $data['txn_customer_email'], 'currency' => $data['txn_currency']]);
                //   $user = User::find($auth_user->id);
                //   $user->subject = 'ECIN Subscription - Payment Voucher (' . $details->city->name . ')';
                //   $user->greeting = "Dear " . $auth_user->name . ",";
                //   $user->email = $auth_user->email;
                //   $user->content = '';
                //   $user->table = 'It is to inform you that, your application has been processed successfully.<br>To proceed further please make payment using the link below to complete your subscription.<br> Voucher will be expire on'.date('d-M-Y', strtotime("+10 days")).'.<table><tr><th><h1>Voucher Number: '. $voucherNo .'</h1></th><th><a href="https://dashboard.ec.com.pk"><img src="https://pay.extreme.institute/assets2/img/ec-logo.png" alt="Extreme Commerce" /></a></th></tr><tr><th><h1>Name: '. $auth_user->name .'</h1></th><th><a href="javascript:;"><img src="https://pay.extreme.institute/assets2/img/one-link.png" alt="One Link" /></a></th></tr><tr><th><h1>Subscription Mode: ECIN Subscription</h1></th>
                //         <th><a href="javascript:;"><img src="https://pay.extreme.institute/assets2/img/kuickpay.png" alt="Kuickpay" /></a></th></tr><tr><th><h1>EC BC Subscription Total in PKR:</h1></th><th><center><b>'. $data['txn_amount'] .' PKR</b></center></th></tr></table>';

                //   $user->voucher = '';
                //   $user->action = "https://app.kuickpay.com/PaymentsBillPayment?cn=" . $voucherNo;
                //   $user->notify(new ecbcRegistrationNotification());
                  return ['response'=>'success','invoice'=>$voucherNo];
        }



}
