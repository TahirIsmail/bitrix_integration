<?php

namespace App\Services;
use App\User;

class KuickpayService {

	public function createVoucher($details)
	{
		$auth_user = $details->user;
		$id = env('EC_PAY_APP_ID');
        $key = env('EC_PAY_KEY');
        $cipher = "aes-128-gcm";
        $iv = env('EC_PAY_IV');
        $iv = hex2bin($iv); // App iv

        $data = array(
            "txn_amount" => $details->amount_deposit,
            "txn_customer_id" => $auth_user->id,
            "txn_customer_name" => $auth_user->name,
            "txn_customer_email" => $auth_user->email,
            "txn_customer_mobile" => $auth_user->phone_number,
            "txn_payment_type" => 'Incubation Subscription',
            "txn_customer_bill_order_id" => $details->registration_no,
            "txn_description" => "Incubation Subscription - (" . ucfirst($auth_user->name) . " - " . $details->city->name . " - " . $details->payment_mode . " - " . $details->booking_type . ")",
            "installments" => 1,
            "txn_gateway_options" => ["kuickpay"],
            "txn_currency" => "PKR",
            "txn_currency_rate" => "Dollar_Rates_2",
            "txn_expiry_datetime" => date('Y-m-d', strtotime("+10 days")),
            "customer_ip" => request()->ip(),
            "txn_platform_return_url" => url("payment/voucher/com"),
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
            CURLOPT_URL => "https://pay.skillsrator.com/api/kuickpay/vouchers/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fieldsData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . env('SR_PAY_API_TOKEN'),
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return ['data'=>$data,'ciphertext'=>$ciphertext,'response'=>$response];
	}

}

?>
