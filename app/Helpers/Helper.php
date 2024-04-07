<?php

namespace App\Helpers;
use DB;
use Illuminate\Support\Facades\Log;

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
            $bitrixId = 1674;
            break;
            case '2 months':
            $bitrixId = 1676;
            break;
            case '2 month':
            $bitrixId = 1676;
            break;
            case '2 month - 10% off':
            $bitrixId = 1678;
            break;
            case '3 months':
            $bitrixId = 1680;
            break;
            case '3 months - 10% off':
            $bitrixId = 1682;
            break;
            case '3 months - 20% off':
            $bitrixId = 1684;
            break;
            case '4 months':
            $bitrixId = 1686;
            break;
            case '4 months - 30% off':
            $bitrixId = 1688;
            break;
            case '5 months':
            $bitrixId = 1690;
            break;
            case '5 months - 40% off':
            $bitrixId = 1692;
            break;
            case '6 months':
            $bitrixId = 1694;
            break;
            case '6 months - 20% off':
            $bitrixId = 1696;
            break;
            case '6 months - 45% off':
            $bitrixId = 1698;
            break;
            default:
            $bitrixId = 1674;
            break;
        }
        return $bitrixId;
    }

}
