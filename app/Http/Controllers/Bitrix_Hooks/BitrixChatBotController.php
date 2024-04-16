<?php

namespace App\Http\Controllers\Bitrix_Hooks;

use Log;
use Carbon\Carbon;
use App\User;
use App\Services\BitrixCallsService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Utilities\Helper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BitrixChatBotController extends Controller
{

    protected $bitrixCall;

    public function __construct()
    {
        $this->bitrixCall = new BitrixCallsService();
    }

    public function handler(Request $request)
    {
    Log::channel('chatBot')->info('----------------Chat Bot Handler running--------------');
    Log::channel('chatBot')->debug(request()->all());

            $botId = 137;
            $clientId = $request['CLIENT_ID'];
            $username = @$request['data']['USER']['NAME'];
            $chatId = $request['data']['PARAMS']['CHAT_ID'];
            $dialogId = $request['data']['PARAMS']['DIALOG_ID'];

            if($request['event']=="ONIMBOTMESSAGEADD"){
                    $message = $this->clean($request['data']['PARAMS']['MESSAGE']);
                    if ($message == '0')
                    {
                        return $this->transferChat($chatId,$botId,$clientId,'queue8',$dialogId);
                    }
                    if ($message == '5' || str_contains($message, 'Karachi'))
                    {
                        return $this->transferChat($chatId,$botId,$clientId,'queue9',$dialogId,$username);
                    }
                    elseif($message == '11' || str_contains($message, 'Lahore')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue11',$dialogId);
                    }
                    elseif($message == '7' || str_contains($message, 'Islamabad')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue13',$dialogId);
                    }
                    elseif($message == '13' || str_contains($message, 'Faisalabad')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue19',$dialogId);
                    }
                    elseif($message == '9' || str_contains($message, 'Multan')){
                        return $this->transferChat($chatId,$botId,$clientId,'15',$dialogId,$username);
                    }
                    // elseif($message == '6' || str_contains($message, 'Peshawar')){
                    //     return $this->transferChat($chatId,$botId,$clientId,'queue16',$dialogId);
                    // }
                    // elseif($message == '7' || str_contains($message, 'Rahim Yar Khan')){
                    //     return $this->transferChat($chatId,$botId,$clientId,'queue18',$dialogId);
                    // }
                    // elseif($message == '6' || str_contains($message, 'Sialkot')){
                    //     return $this->transferChat($chatId,$botId,$clientId,'queue14',$dialogId);
                    // }
                    else{
                    Log::channel('chatBot')->info('ImBot Else Running');
                    // Reply 6 for Peshawar:
                    // Reply 7 for Rahim Yar Khan:
                    // Reply 6 for Sialkot:
                    $message = "
                    Greetings ".$username."

                    We are pleased to welcome you to Skillsrator! To ensure we provide you with the best assistance possible, kindly indicate your city code by selecting the corresponding number:

                    Reply 5 for Karachi.
                    Reply 11 for Lahore.
                    Reply 7 for Islamabad.
                    Reply 13 for Faisalabad.
                    Reply 9 for Multan.
                    Reply 0 for other cities.

                    We will promptly connect you with one of our representatives who will be able to assist you with your inquiry.";

                    if($request['data']['PARAMS']['MESSAGE'] != 4){
                        $dialogId = $request['data']['PARAMS']['DIALOG_ID'];
                        $this->sendCustomMessage($botId,$dialogId,$message,$clientId);
                    }
                    else{
                        $data2=array(
                            "CHAT_ID"=>$chatId,
                            "USER_ID"=>$request['data']['PARAMS']['FROM_USER_ID'],
                            'CLIENT_ID'=>$clientId
                            );
                        $queryData      = http_build_query($data2);
                        $result_data= $this->bitrixCall->sendImbotCurlRequest($queryData,"setOwner","imbot.chat");
                    }
                }
    }
}

public function transferChat($chatId,$botId,$clientId,$transferQueue,$dialogId,$username='')
{   //dd('here is transfer chat');

        $message = 'Dear '.$username.':

        Thank you for your interest in Extreme Commerce Incubator.

Our agents are currently at capacity and will reach out to you in next 48 to 72 hours.

Meanwhile please feel free to go through this video for a better understanding of our offerings https://youtu.be/S9WKe-nPy6Q';

    $this->sendCustomMessage($botId,$dialogId,$message,$clientId);

    $data = array("CHAT_ID"=>$chatId,"USERS"=>array(1),"BOT_ID"=>$botId,'CLIENT_ID' =>$clientId);
    $queryData      = http_build_query($data);
    $result_data= $this->bitrixCall->sendImbotCurlRequest($queryData,"add","im.chat.user");
    $data2=array(
        "CHAT_ID"=>$chatId,
        "TRANSFER_ID"=>$transferQueue,
        );
    $queryData      = http_build_query($data2);
    $result_data= $this->bitrixCall->sendImbotCurlRequest($queryData,"transfer","imopenlines.operator");
    print_r($result_data);
    die();
}

public function sendCustomMessage($botId,$dialogId,$message,$clientId)
{
    $data = [
            'BOT_ID' => $botId, // ID of chatbot that sends request. Is optional, there is only one chatbot
            'DIALOG_ID' => $dialogId, // Dialog ID: it can be either USER_ID, or chatXX - where XX is chat ID, transmitted in events ONIMBOTMESSAGEADD and ONIMJOINCHAT
            'MESSAGE' => $message , // Message text
            'ATTACH' => '', // Attachment, optional field
            'KEYBOARD' => '', // Keyboard, optional field
            'MENU' => '', // Context menu, optional field
            'SYSTEM' => 'N', // Display messages as system messages, optional field, by default 'N'
            'URL_PREVIEW' => 'Y', // Convert links to rich-links, optional field, by default 'Y'
            'CLIENT_ID'=>$clientId
        ];

    $queryData   = http_build_query($data);
    $result_data = $this->bitrixCall->sendImbotCurlRequest($queryData,"add","imbot.message");
}

function clean($string) {
   $string = str_replace('', ' ', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
}

}
