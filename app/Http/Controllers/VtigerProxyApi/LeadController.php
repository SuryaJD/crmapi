<?php

namespace App\Http\Controllers\VtigerProxyApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notification = json_decode($request->getContent(), true);

        Log::info('Data',$notification);

        $data = json_decode($notification['Message'],true);

        $response = Http::post("https://crm.aerem.co/modules/Webforms/capture.php", [
            '__vtrftk'          => 'sid:5fd6c32e68d77ecd612ee689833d51c72e9c19ce,1661181177',
            'publicid'          => '883bcfb8fb3729fed71784919bb7c685',
            'urlencodeenable'   => '1',
            'name'              => 'Lead Creation Api',
            'lastname'          =>  $data['entity_name'],
            'cf_862'            => 'Address Line 1',
            'cf_864'            => 'Address Line 2',
            'cf_866'            => 'Pincode',
            'cf_868'            => 'District',
            'state'             => 'state',
            'cf_870'            => 'Mobile number',
            'cf_872'            =>  $data['entity_email'],
            'cf_874'            => 'Project Size',
            'cf_876'            => 'Installation Address Line One',
            'cf_878'            => 'Installation Address Line Two',
            'cf_880'            => 'Installation Pincode',
            'cf_882'            => 'Installation District',
            'cf_884'            => 'Installation State',
        ]);

        Log::info($response->body());



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
