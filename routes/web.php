<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bitrix_Hooks\BitrixHooksController;
use App\Http\Controllers\Bitrix_Hooks\BitrixChatBotController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\IncubatorController;
use App\Http\Controllers\DigitalIncubationController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DincPlucCommunityController;

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
Route::post('coworking/calculate',[IncubatorController::class,'calculateCoworkingSubscription']);
Route::get('/co-working-space',function(){ return view('incubator/coworking_space');});
Route::post('/store-coworking-space',[IncubatorController::class,'storeCoworkingSpace']);

Route::get('/digital-incubation',[DigitalIncubationController::class,'create']);
Route::post('/store-digital-incubator',[DigitalIncubationController::class,'store']);
Route::get('/get-country-cities', [DigitalIncubationController::class,'getCitiesByCountryId']);

Route::get('/community',[CommunityController::class,'create']);
Route::post('/store-community',[CommunityController::class,'store']);

Route::get('/dinc-plus-community',[DincPlucCommunityController::class,'create']);
Route::post('/store-dinc-plus-community',[DincPlucCommunityController::class,'store']);

Route::prefix('bitrix')->group(function(){
    Route::any('/deal-created', [BitrixHooksController::class,'dealCreated']);
    Route::any('/incubation-activation', [BitrixHooksController::class,'incubationActivation']);
    Route::any('/program-activation', [BitrixHooksController::class,'programActivation']);
    Route::any('/create-invoice', [BitrixHooksController::class,'createBitrixInvoice']);
    Route::any('/create-deal-invoice', [BitrixHooksController::class,'createBitrixDealInvoice']);
    Route::any('/chatbothandler',[BitrixChatBotController::class,'handler']);
});
Route::any('payment/{id}', [PaymentController::class,'createPayments']);
Route::any('payment/thankyou', [PaymentController::class,'paymentThankyou']);
Route::any('/trainings/payment/{id}', [PaymentController::class,'show']);
Route::any('transaction-complete', [PaymentController::class,'transactionComptele']);
Route::any('incubator-transaction-complete', [PaymentController::class,'IncubatorTransactionComptele']);
Route::any('/trainings-payment/thankyou', [PaymentController::class,'payment_thankyou']);
Route::get('/incomplete', function () {
            return View::make("payments.canceled-page");
        });
// Route::prefix('payment')->group(function(){
//     // Route::any('/{id}', [PaymentController::class,'show']);
//     Route::get('/voucher', [PaymentController::class,'zeroTransactionComptele']);

//
// });
Route::get('/reload-captcha', [CaptchaController::class, 'reloadCaptcha']);
Route::post('/get-courses',   [DigitalIncubationController::class, 'getCourses']);

Route::prefix('admin')->group(function(){
    Auth::routes();
    Route::get('/home', function(){
        return redirect('admin');
    })->name('home');
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index']);
    Route::post('incubator/changeDipositAmount', [App\Http\Controllers\Admin\IncubatorController::class, 'updateDipositAmount']);
    Route::post('incubator/delete-incubatee', [App\Http\Controllers\Admin\IncubatorController::class, 'deleteIncubatee']);
    Route::post('incubator/delete-incubatee-subscription', [App\Http\Controllers\Admin\IncubatorController::class, 'deleteIncubateeSubscription']);
    Route::get('incubator/regenerate-voucher/{id}', [App\Http\Controllers\Admin\IncubatorController::class, 'voucherRecreate']);
    Route::post('incubator/search', [App\Http\Controllers\Admin\IncubatorController::class, 'searchIncubateeData']);
    Route::get('/incubator/search',[App\Http\Controllers\Admin\IncubatorController::class, 'searchIncubatee']);
    Route::post('dtc-community-modifications', [App\Http\Controllers\Admin\DigitalIncubationController::class, 'searchData']);
    Route::get('/dtc-community-modifications',function(){
        return view('admin.search_candidate');
    });

    //Coupon
    Route::resource('coupons',App\Http\Controllers\Admin\CouponController::class);
    //Courses
    Route::resource('courses',App\Http\Controllers\Admin\CoursesController::class);
    // Route::get('import_data', [App\Http\Controllers\Admin\AdminController::class, 'import_data']);
    Route::get('/search/users',[App\Http\Controllers\Admin\UsersController::class,'index']);
    Route::post('/search/users',[App\Http\Controllers\Admin\UsersController::class,'searchCandidate']);
    Route::get('/user/mini-detail/{id}',[App\Http\Controllers\Admin\UsersController::class,'miniDetail']);
});
