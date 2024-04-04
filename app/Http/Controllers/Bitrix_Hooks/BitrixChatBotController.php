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

            $botId = 74220;
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
                    if ($message == '1' || str_contains($message, 'Karachi'))
                    {
                        return $this->transferChat($chatId,$botId,$clientId,'queue10',$dialogId,$username);
                    }
                    elseif($message == '2' || str_contains($message, 'Lahore')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue12',$dialogId);
                    }
                    elseif($message == '3' || str_contains($message, 'Islamabad')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue22',$dialogId);
                    }
                    elseif($message == '4' || str_contains($message, 'Faisalabad')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue24',$dialogId);
                    }
                    elseif($message == '5' || str_contains($message, 'Multan')){
                        return $this->transferChat($chatId,$botId,$clientId,'queue20',$dialogId,$username);
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
                    Hello👋, Welcome to Extreme Commerce! To better assist you, please select your city code from the following options by pressing the corresponding number:

                    Reply 1 for Karachi:
                    Reply 2 for Lahore:
                    Reply 3 for Islamabad:
                    Reply 4 for Faisalabad:
                    Reply 5 for Multan:
                    Reply 0 for other Cities:

                    Once you've made your selection, we'll connect you with one of our representatives who can help you with your inquiry.
                        ";

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
    if ($username!='' AND $transferQueue == 'queue10') {
        $message = 'Dear '.$username.':

Greetings from Skillsrator (Extreme Commerce)

You have shown interest to Learn, Earn and Grow through EC Incubator, Karachi.

You can visit our campus on daily bases for 1-1 CONSULTATION session from 10:00 a.m to 4:00 p.m OR you can attend our FREE ORIENTATION SESSION on Tuesday at 1.00p.m for detailed understanding in multiple ecommerce pathways.
" You can also avail 1 MONTH FREE SUBSCRIPTION "
Location:
Skillsrator (Extreme Commerce) Plaza, Tariq Road, Karachi; https://maps.app.goo.gl/1h4eQnj5hNrCKKeH6 ';
       } else if($username!='' AND $transferQueue == 'queue20') {
$message = 'Dear '.$username.':

Welcome to Skillsrator (Extreme Commerce Incubation Network) - A place to connect with Trainers, Digital Experts & Freelancer to expand your knowledge and opportunities!
Discover the power of networking opportunities, hands-on learning, and structured training programs. Our training sessions cover a range of skill sets, from Digital Marketing and much more.
We are providing 3 new mentioned training .You can attend one-day free demo classes.
1- Graphic designing
2- Amazon Wholesale
3- Shopify
Following trainings are ongoing, you can also attend these trainings as well,
1) SEO
2) FREELANCING
3) AMAZON PL
4) EBAY
5) DIGITAL MARKETING
6) TIKTOK SHOP

Located at 57-A, Near Bilal Masjid Gulgusht Colony, Multan, our doors are open from 10 am to 10 pm; Monday to Saturday. Feel free to drop by and explore how ECIN can help you in learning digital skills to pave your way to earn in dollars.
For inquiries,call us at 061 6217082';
    }else{
        $message = 'Dear '.$username.':

        Thank you for your interest in Extreme Commerce Incubator.

Our agents are currently at capacity and will reach out to you in next 48 to 72 hours.

Meanwhile please feel free to go through this video for a better understanding of our offerings https://youtu.be/S9WKe-nPy6Q';
       }
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
