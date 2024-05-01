<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bitrix_Hooks\BitrixHooksController;
use App\Http\Controllers\Bitrix_Hooks\BitrixChatBotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\IncubatorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('incubator')->group(function (){
    Route::post('/calculate',[IncubatorController::class,'calculateSubscription']);
    Route::post('/summary',[IncubatorController::class,'showSummary']);
    Route::post('/subscription_period',[IncubatorController::class,'showSubscriptionPeriod']);
    Route::post('/store',[IncubatorController::class,'store']);
    Route::get('/renewal',function(){ return view('incubator/renewal');});
    Route::post('/store_renewal',[IncubatorController::class,'store_renewal']);
    Route::post('/coupon',[App\Http\Controllers\IncubatorController::class, 'couponDetails']);
});

Route::prefix('bitrix')->group(function(){
    Route::any('/deal-created', [BitrixHooksController::class,'dealCreated']);
    Route::any('/incubation-activation', [BitrixHooksController::class,'incubationActivation']);
    Route::any('/create-invoice', [BitrixHooksController::class,'createBitrixInvoice']);
    Route::any('/create-deal-invoice', [BitrixHooksController::class,'createBitrixDealInvoice']);
    Route::any('/chatbothandler',[BitrixChatBotController::class,'handler']);
});
Route::any('payment/{id}', [PaymentController::class,'show']);
Route::any('transaction-complete', [PaymentController::class,'transactionComptele']);
Route::any('incubator-transaction-complete', [PaymentController::class,'IncubatorTransactionComptele']);
Route::any('payment/thankyou', [PaymentController::class,'payment_thankyou']);
Route::get('/incomplete', function () {
            return View::make("payments.canceled-page");
        });
// Route::prefix('payment')->group(function(){
//     // Route::any('/{id}', [PaymentController::class,'show']);
//     Route::get('/voucher', [PaymentController::class,'zeroTransactionComptele']);

//
// });


Route::prefix('admin')->group(function(){
    Auth::routes();
    Route::get('/home', function(){
        return redirect('admin');
    })->name('home');
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index']);
    Route::get('incubatee-details', [App\Http\Controllers\Admin\AdminController::class, 'incubatee_details']);
    //Coupon
    Route::resource('coupons',App\Http\Controllers\Admin\CouponController::class);
    // Route::get('import_data', [App\Http\Controllers\Admin\AdminController::class, 'import_data']);
});
