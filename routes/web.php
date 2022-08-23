<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Invoker\SnsReceiver;
use App\Http\Controllers\Invoker\WebhookHandler;
use App\Http\Controllers\VtigerProxyApi\LeadController as VtigerProxyApiLeadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test',function ()
{
    return view('welcome');
});

Route::group([
    'prefix' => 'api/v1/',
], function () {
    Route::apiResource('leads', LeadController::class);
});

Route::get('/',function(){
    return "working";
});

Route::post('sns/webhook',SnsReceiver::class);

// Route::post('sns/webhook',function (Request $request)
// {
//     Log::alert('SNS Webhook Received', $request->all());
//     Log::info($request->getContent());
// });
