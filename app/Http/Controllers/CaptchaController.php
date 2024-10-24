<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\capchaRule;

class CaptchaController extends Controller
{
    public function reloadCaptcha()
    {
        $configCaptchaType = config('captcha.default.type');

        // Initialize variable to store captcha type
        $captchaType = '';

        // If the config number is 0, set captcha type to 'flat' (alphanumeric)
        // If it's 1, set captcha type to 'math'
        $captchaType = 'inverse';

        // the generated type will be stored in the captchaImage
        $captchaImage = captcha_img($captchaType);

        // Return JSON response with the generated captcha image
        return response()->json(['captcha' => $captchaImage]);
    }

    public static function generateCaptcha()
    {
        $configCaptchaType = config('captcha.default.type');

        // If the config number is 0, generate a 'flat' (alphanumeric) captcha,
        // otherwise, generate a 'math' captcha
        return captcha_img('inverse');
    }
}
