<?php

namespace App\Services;
use Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
use App\Helpers\Helper;

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

    public function sendRequest($fields,$products = null,$action="add",$method="crm.lead")
    {
        if (env('APP_ENV') == "local") {
         $response = Http::get(env('BITRIX_URL').$method.'.'.$action , $fields);
         if (isset($response) and $response->json('result') !== '') {
            if ($products != null) {
             $product['id'] = $response->json('result');
             $product['rows'] = $products;
             Http::get(env('BITRIX_URL').$method.'.productrows.set.json', $product);
            }
            return $response->json('result');
         }
         if(isset($response) and $response->json('error') !== ''){
                dispatch(new BitrixMissingLeads($registration));
            }
        }
    }

    public function sendImbotCurlRequest($queryData,$action="add",$method="crm.lead"){
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

    public function createIncLead($data,$request,$programId)
    {
        //Check if he is already contact of bitrix
        $getData = Http::get(env('BITRIX_URL').'crm.contact.list', ['FILTER[EMAIL]' => $data->incubatee->email]);

        if (!empty(json_decode($getData)->result)) {
            $fields['CONTACT_ID'] = json_decode($getData)->result[0]->ID;
        } else {
            //Create Bitrix contact
            $fields['NAME'] = $data->incubatee->user_name;
            $fields['EMAIL'][0]['VALUE'] = $data->incubatee->email;
            $fields['EMAIL'][0]['VALUE_TYPE'] = 'WORK';
            $fields['PHONE'][0]['VALUE'] = $data->incubatee->whatsapp_number;
            $fields['PHONE'][0]['VALUE_TYPE'] = 'WORK';
        }

        if ($programId == 207) {//done
           $productRows = array(["PRODUCT_ID" => Helper::incubatorCityBitrixId($data->city_id), "PRICE" => $data->totalAmount, "QUANTITY" => 1]);
           $programTitle = 'Incubator Only';
           $preferredTiming = (($data->shift == 'day')?1:(($data->shift == 'evening')?2:5)) ;
           $batch = 'INC'.now()->format('MY');
        }elseif($programId == 209){//done
           $productRows = array(["PRODUCT_ID" => Helper::incubatorCityBitrixId($data->city_id), "PRICE" => $data->totalAmount, "QUANTITY" => 1]);
           $programTitle = 'Incubation Online';
           $batch = 'INCO'.now()->format('MY');
           $preferredTiming = '';
        }elseif($programId == 1868){
           $productRows = array(["PRODUCT_ID" => Helper::incubatorCityBitrixId($data->city_id), "PRICE" => $data->totalAmount, "QUANTITY" => $data->subscription_period]);
           $programTitle = 'Co-Working Space';
           $preferredTiming = '';
           $batch = 'COINC'.now()->format('MY');
        }

        //Create common fields array for bitirx
        $fields['TITLE'] = $data->incubatee->user_name.' - '.@$data->city.' - ('.$data->subscription_period.') - '.$programTitle;
        $fields['STATUS_ID']            = 'NEW';
        $fields['CURRENCY_ID']          = 'PKR';
        $fields['OPPORTUNITY']          = $data->totalAmount;
        $fields['UF_CRM_1707476256']    = Helper::incubatorCityBitrixId($data->city_id);//done
        $fields['UF_CRM_1710586724']    = 'Pakistan'; //done
        $fields['UF_CRM_1710585498']    = (($data->incubatee->gender == 'male') ? 2521 : 2523); //done
        $fields['BIRTHDATE']            = $data->incubatee->date_of_birth ?? ''; //done
        $fields['UF_CRM_1707309956']    = $programId; //Incubator Only Program done
        $fields['UF_CRM_1663458377297'] = $data->incubatee->facebook_profile;
        $fields['UF_CRM_1707992707'] = $data->incubatee->cnic_number; //done
        // $fields['UF_CRM_1675251200'] = $data->coupon;
        // $fields['UTM_SOURCE']        = $request->pi_utm_source;
        // $fields['UTM_MEDIUM']        =  $request->pi_utm_medium;
        // $fields['UTM_CAMPAIGN']      = $request->pi_utm_campaign;
        $fields['UF_CRM_1707476450'] = Helper::incubatoSubscriptionTypeBitrixId($data->subscription_period);
        $fields['UF_CRM_1707475905'] = $preferredTiming;
        // $fields['UF_CRM_1664188710'] = $bitrixBcStdId;
        $fields['UF_CRM_1710672374'] = $batch; //Batch

        $fields = ['fields' => $fields];

        //Bitrix Lead Create Code Start
        $lead_id = $this->sendRequest($fields,$productRows);
        return $lead_id;
    }

    public function createIncDeal($registration,$data)
    {
        Log::channel('bitrix')->info('==================Bitrix Auto Assign Incubator Deal Create Start=============== ' . Date('Y-m-d H:i:s'));
        $bitrixBcStdId = ((Helper::isBootCampStd($data->incubatee->tags) == true) ? 1142 : 1144);
        if (strpos($data->incubatee->email, 'ecc.edu.pk') !== false) {
            $bitrixBcStdId = 1158;
        }
        $subCategory = '';
        if ($registration->program->id == 3 || $registration->program->id == 6 || $registration->program->id == 7)
        {
        $subCategory = (($registration->program->id == 3)?1150:(($registration->program->id == 6)?1146:1148));
        }

        //Check if he is already contact of bitrix
        $getData = Http::get('https://extremecommerce.bitrix24.com/rest/18/hu74sl94xow1jvh2/crm.contact.list', ['FILTER[EMAIL]' => $data->incubatee->email]);
        if (!empty(json_decode($getData)->result)) {
            $fields['CONTACT_ID'] = json_decode($getData)->result[0]->ID;
        } else {
            //Create Bitrix contact
            $fields['FIELDS']['NAME'] = $data->incubatee->user_name;
            $fields['FIELDS']['EMAIL'][0]['VALUE'] = $data->incubatee->email;
            $fields['FIELDS']['EMAIL'][0]['VALUE_TYPE'] = 'WORK';
            $fields['FIELDS']['PHONE'][0]['VALUE'] = $data->incubatee->whatsapp_number;
            $fields['FIELDS']['PHONE'][0]['VALUE_TYPE'] = 'WORK';
            $contactId = $this->sendRequest($fields,null,'add','crm.contact');
            $fields['CONTACT_ID'] = $contactId;
        }
        //Create common fields array for bitirx
        $fields['TITLE'] =  $data->incubatee->user_name.' - '.$data->city.' - ('.$data->subscription_period.')';
        $fields['STATUS_ID']            = 'C14:NEW';
        $fields['CURRENCY_ID']          = 'PKR';
        $fields['OPPORTUNITY']          = $data->totalAmount;
        $fields['UF_CRM_1664375027']    = $data->city->bitrix_city_id;
        $fields['UF_CRM_1664375057']    = 28; //country Pakistan only
        $fields['UF_CRM_1664374997']    = (($data->incubatee->gender == 'male') ? 18 : 20);
        $fields['UF_CRM_1664030660']    = $data->incubatee->date_of_birth ?? '';
        $fields['UF_CRM_1664375199']    = 50; //Incubator Only Program
        $fields['UF_CRM_1663458377297'] = $data->incubatee->facebook_profile;
        $fields['UF_CRM_63D8F2FB6E2BE'] = $data->incubatee->cnic_number;
        $fields['UF_CRM_1675251200'] = $data->coupon;
        $fields['UF_CRM_1675331322'] = Helper::incubatoSubscriptionTypeBitrixId($data->subscription_period);
        $fields['UF_CRM_1671005865'] = ($data->shift == 'day')?1632:(($data->shift == 'evening')?1634:1636);
        $fields['UF_CRM_1664375287'] = $subCategory;
        $fields['UF_CRM_1664375250'] = $bitrixBcStdId;
        $fields['UF_CRM_6346CD7E3D06C'] = '';
        $fields['UF_CRM_1675846599826'] = @$data->city->code.'-'.@$data->incubator->incubatee_code;
        $fields['UF_CRM_1675176530'] = '1st Installment Plan';
        $fields['UF_CRM_1677148403'] = (($data->isHavingDiscount())?'More than 6 month 50% discount.':'');
        $fields['UF_CRM_1664375219'] = 'INC'.now()->format('MY'); //Batch
        $products = array(["PRODUCT_ID" => Helper::incubatorCityBitrixId($data->city_id), "PRICE" => $data->totalAmount, "QUANTITY" => 1]);
        $fields = array('fields'=>$fields);
        $dealId = $this->sendRequest($fields,$products,'add','crm.deal');

        Log::channel('bitrix')->info('==================Bitrix Auto Assign Incubator Deal Create Complete=============== ' . Date('Y-m-d H:i:s'));
        return $dealId;

    }

}
?>
