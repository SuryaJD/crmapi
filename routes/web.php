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
    $response = Http::withoutVerifying()->withHeaders([
        'Content-Type' => 'application/x-www-form-urlencoded',
    ])->withOptions([
        'verify' => false
    ])->post("https://crm.aerem.co/modules/Webforms/capture.php", [
        '__vtrftk'          => 'sid:5fd6c32e68d77ecd612ee689833d51c72e9c19ce,1661181177',
        'publicid'          => '883bcfb8fb3729fed71784919bb7c685',
        'urlencodeenable'   => '1',
        'name'              => 'Lead Creation Api',
        'lastname'          =>  'entity_name two three',
        'cf_862'            => 'Address Line 1',
        'cf_864'            => 'Address Line 2',
        'cf_866'            => 'Pincode',
        'cf_868'            => 'District',
        'state'             => 'state',
        'cf_870'            => 'Mobile number',
        'cf_872'            =>  'entity_email',
        'cf_874'            => 'Project Size',
        'cf_876'            => 'Installation Address Line One',
        'cf_878'            => 'Installation Address Line Two',
        'cf_880'            => 'Installation Pincode',
        'cf_882'            => 'Installation District',
        'cf_884'            => 'Installation State',
    ]);
    // return "hello";
});

Route::post('sns/webhook',SnsReceiver::class);

// Route::post('sns/webhook',function (Request $request)
// {
//     Log::alert('SNS Webhook Received', $request->all());
//     Log::info($request->getContent());
// });
