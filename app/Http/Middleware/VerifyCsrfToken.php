<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'bitrix/create-invoice',
        'bitrix/deal-created',
        'bitrix/create-deal-invoice',
        'bitrix/incubation-activation',
        'bitrix/chatbothandler',
        'payment',
        'transaction-complete',
        'incubator-transaction-complete',
        'payment/thankyou'
    ];
}
