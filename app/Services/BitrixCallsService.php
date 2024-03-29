<?php

namespace App\Services;
use Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class BitrixCallsService {

    public function handle($data,$action='update',$method='crm.lead')
    {
        $queryFields = http_build_query($data);
        $this->sendCurlRequest($queryFields,$action,$method);
    }


    // public function updateBitrixDealCopy($DealId,$installment)
    // {
    //     $bitrixObj=[
    //     'ID' => $DealId,
    //     'FIELDS[UF_CRM_1675176530]' => (($installment == 2)?'2nd':(($installment == 3)?'3rd':$installment)).' installment',
    //     ];
    //     $queryFields   = http_build_query($bitrixObj);
    //     $deal_id = $this->sendCurlRequest($queryFields,'update','crm.deal');
    // }

    public function sendCurlRequest($queryData,$action="add",$method="crm.lead"){
         // if (env('APP_ENV') != 'production') {
         //    return false;
         // }

         $BITRIX_URL = env('BITRIX_URL');
         $queryUrl              = $BITRIX_URL."$method.$action/";
         $curl                  = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_POST           => 1,
         CURLOPT_HEADER         => 0,
         CURLOPT_RETURNTRANSFER => 1,
         CURLOPT_URL            => $queryUrl,
         CURLOPT_POSTFIELDS     => $queryData,
         ));
         $result = curl_exec($curl);
         curl_close($curl);
         $result = json_decode($result, 1);
         return  $result;
    }

    public function sendImbotCurlRequest($queryData,$action="add",$method="crm.lead"){
         $BITRIX_URL = 'https://ice.bitrix24.com/rest/60352/0lfacoma9f89y9k2/';
         $queryUrl              = $BITRIX_URL."$method.$action/";
         $curl                  = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_POST           => 1,
         CURLOPT_HEADER         => 0,
         CURLOPT_RETURNTRANSFER => 1,
         CURLOPT_URL            => $queryUrl,
         CURLOPT_POSTFIELDS     => $queryData,
         ));
         $result = curl_exec($curl);
         curl_close($curl);
         $result = json_decode($result, 1);
         return  $result;
    }

}
?>
