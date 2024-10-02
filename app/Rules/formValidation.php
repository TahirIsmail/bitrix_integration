<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\DigitalIncubationRegistration;

class formValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }


    public static function checkAlreadyEnrolled($user_id)
    {
        $isErolled = DigitalIncubationRegistration::where('user_id',$user_id)->latest()->first();
        return $isErolled;
    }
}
