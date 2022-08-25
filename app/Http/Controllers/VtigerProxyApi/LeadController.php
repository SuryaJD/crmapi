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

        Log::info('notification', $notification);

        $data = json_decode($notification['Message'], true);

        // Log::error($notification['MessageAttributes']['Application']['Value']);

        if (isset($notification['MessageAttributes']) && $notification['MessageAttributes']['Application']['Value'] == 'aerem') {
            Log::info("message", $data);

            $response = Http::asForm()->withOptions([
                'version' => 2,
            ])->post("https://crm.aerem.co/modules/Webforms/capture.php", [
                '__vtrftk'          => 'sid:5fd6c32e68d77ecd612ee689833d51c72e9c19ce,1661181177',
                'publicid'          => '883bcfb8fb3729fed71784919bb7c685',
                'urlencodeenable'   => '1',
                'name'              => 'Lead Creation Api',
                'lastname'          =>  $data['entity_name'], // Name
                'cf_862'            =>  $data['entity_address_l1'],  // 'Address Line 1',
                'cf_864'            =>  $data['entity_address_l2'],  // 'Address Line 2',
                'cf_900'            =>  $data['entity_pin'], //'Pincode',
                'cf_868'            =>  $data['entity_dist'], // 'District',
                'state'             =>  $data['entity_state'], //'state',
                'cf_896'            =>  $data['entity_phone'], //'Mobile number',
                'cf_872'            =>  $data['entity_email'], // Email
                'cf_874'            =>  $data['proposed_project_size_kw'], // 'Project Size',
                'cf_876'            =>  $data['site_address_l1'], // 'Installation Address Line One',
                'cf_878'            =>  $data['site_address_l2'], //'Installation Address Line Two',
                'cf_898'            =>  $data['site_pin'], //'Installation Pincode',
                'cf_882'            =>  $data['site_dist'], // 'Installation District',
                'cf_884'            =>  $data['site_state'], // 'Installation State',
                'cf_894'            =>  strval($data['installer_lead_id']),
                'cf_908'            =>  $data['electric_bill'],
                'cf_910'            =>  $data['proposal'],
            ]);

            Log::debug('Sent to vtiger', [
                '__vtrftk'          => 'sid:5fd6c32e68d77ecd612ee689833d51c72e9c19ce,1661181177',
                'publicid'          => '883bcfb8fb3729fed71784919bb7c685',
                'urlencodeenable'   => '1',
                'name'              => 'Lead Creation Api',
                'lastname'          =>  $data['entity_name'], // Name
                'cf_862'            =>  $data['entity_address_l1'],  // 'Address Line 1',
                'cf_864'            =>  $data['entity_address_l2'],  // 'Address Line 2',
                'cf_900'            =>  $data['entity_pin'], //'Pincode',
                'cf_868'            =>  $data['entity_dist'], // 'District',
                'state'             =>  $data['entity_state'], //'state',
                'cf_896'            =>  $data['entity_phone'], //'Mobile number',
                'cf_872'            =>  $data['entity_email'], // Email
                'cf_874'            =>  $data['proposed_project_size_kw'], // 'Project Size',
                'cf_876'            =>  $data['site_address_l1'], // 'Installation Address Line One',
                'cf_878'            =>  $data['site_address_l2'], //'Installation Address Line Two',
                'cf_898'            =>  $data['site_pin'], //'Installation Pincode',
                'cf_882'            =>  $data['site_dist'], // 'Installation District',
                'cf_884'            =>  $data['site_state'], // 'Installation State',
                'cf_894'            =>  strval($data['installer_lead_id']),
                'cf_906'            =>  $data['electric_bill']
            ]);

            Log::info($response->body());
        }
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
