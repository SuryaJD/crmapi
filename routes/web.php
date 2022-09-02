<?php

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Models\LeadCustomField;
use Illuminate\Support\Facades\DB;
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
// Route::get('/test',function ()
// {
//     // dd(LeadCustomField::select('*')->where(['cf_868' => '255'])->get());
//     // return view('welcome');

//     // dd(Lead::all());

//     $dsn = "mysql:host=".config('database.connections.mysql.host').";dbname=".config('database.connections.mysql.database');

//     $pdo = new PDO($dsn, config('database.connections.mysql.username'), config('database.connections.mysql.password'));

//     // $stm = $pdo->query("SELECT * from vtiger_leadscf");

//     // dump($stm->fetchAll(PDO::FETCH_ASSOC));


//     // $q = $pdo->prepare("DESCRIBE vtiger_leaddetails");
//     $q = $pdo->prepare("DESCRIBE vtiger_leadscf");
//     $q->execute();
//     $custom_fields = $q->fetchAll(PDO::FETCH_OBJ);

//     dump($custom_fields);

//     // while ($row = $stm->fetchObject()) {
//     //     dump($row);
//     // }




// });

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
