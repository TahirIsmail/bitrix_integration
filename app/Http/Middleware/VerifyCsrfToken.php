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
        'deal-created',
        'bitrix/chatbothandler',
        'payment',
        'transaction-complete',
        'incubator-transaction-complete',
        'payment/thankyou'
    ];
}
