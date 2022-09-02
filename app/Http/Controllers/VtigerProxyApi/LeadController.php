<?php

namespace App\Http\Controllers\VtigerProxyApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\LeadCustomField;
use Illuminate\Support\Facades\Http;
use Salaros\Vtiger\VTWSCLib\WSClient;

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
        // Log::error($notification['MessageAttributes']['Application']['Value']);

        $notification = json_decode($request->getContent(), true);

        if (isset($notification['MessageAttributes']) && $notification['MessageAttributes']['Application']['Value'] == 'aerem') {

            Log::info('notification', $notification);

            $data = json_decode($notification['Message'], true);

            $formData =  [
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
                'cf_886'            =>  'PENDING'
            ];

            if (isset($data['installer']) && is_array($data['installer'])) {
                $formData['cf_912']            =  $data['installer']['id'] ?? null;
                $formData['cf_918']            =  $data['installer']['name'] ?? null;
                $formData['cf_914']            =  $data['installer']['email'] ?? null;
                $formData['cf_916']            =  $data['installer']['contact'] ?? null;
            }

            if (isset($data['channel_manager']) && is_array($data['channel_manager'])) {
                $formData['cf_930']            =  $data['channel_manager']['id'] ?? null;
                $formData['cf_932']            =  $data['channel_manager']['name'] ?? null;
                $formData['cf_938']            =  $data['channel_manager']['email'] ?? null;
                $formData['cf_936']            =  $data['channel_manager']['contact'] ?? null;
            }

            if (isset($data['loan_sales_manager']) && is_array($data['loan_sales_manager'])) {
                $formData['cf_942']            =  $data['loan_sales_manager']['id'] ?? null;
                $formData['cf_944']            =  $data['loan_sales_manager']['name'] ?? null;
                $formData['cf_948']            =  $data['loan_sales_manager']['email'] ?? null;
                $formData['cf_946']            =  $data['loan_sales_manager']['contact'] ?? null;
            }

            Log::info("message", $data);

            $client = new WSClient('https://crm.aerem.co/', 'admin', 'Pt6Oh5A3YhofNY3');

            try {
                $added = $client->entities->createOne('Leads', $formData);
                Log::info('Lead Added', $added);
            } catch (\Throwable $th) {
                Log::error('vtiger webservice error', [
                    'error' => $th->getMessage(),
                ]);
            }
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
    public function update(Request $request)
    {
        $notification = json_decode($request->getContent(), true);

        if (isset($notification['MessageAttributes']) && $notification['MessageAttributes']['Application']['Value'] == 'aerem') {

            $data = json_decode($notification['Message'], true);

            $leadId = optional(LeadCustomField::where(['cf_894' => $data['installer_lead_id']])->first())->leadId;

            // if (is_numeric($leadId)) {
                $formData =  [
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
                    'cf_886'            =>  $data['status']
                ];

                if (isset($data['installer']) && is_array($data['installer'])) {
                    $formData['cf_912']            =  $data['installer']['id'] ?? null;
                    $formData['cf_918']            =  $data['installer']['name'] ?? null;
                    $formData['cf_914']            =  $data['installer']['email'] ?? null;
                    $formData['cf_916']            =  $data['installer']['contact'] ?? null;
                }

                if (isset($data['channel_manager']) && is_array($data['channel_manager'])) {
                    $formData['cf_930']            =  $data['channel_manager']['id'] ?? null;
                    $formData['cf_932']            =  $data['channel_manager']['name'] ?? null;
                    $formData['cf_938']            =  $data['channel_manager']['email'] ?? null;
                    $formData['cf_936']            =  $data['channel_manager']['contact'] ?? null;
                }

                if (isset($data['loan_sales_manager']) && is_array($data['loan_sales_manager'])) {
                    $formData['cf_942']            =  $data['loan_sales_manager']['id'] ?? null;
                    $formData['cf_944']            =  $data['loan_sales_manager']['name'] ?? null;
                    $formData['cf_948']            =  $data['loan_sales_manager']['email'] ?? null;
                    $formData['cf_946']            =  $data['loan_sales_manager']['contact'] ?? null;
                }

                Log::info("message", $data);

                $client = new WSClient('https://crm.aerem.co/', 'admin', 'Pt6Oh5A3YhofNY3');

                $vtigerLeadId = "10x".$leadId;

                try {
                    $added = $client->entities->updateOne('Leads', $vtigerLeadId, $formData);
                    Log::info('Lead updated', $added);
                } catch (\Throwable $th) {
                    Log::error('vtiger webservice error', [
                        'error' => $th->getMessage(),
                    ]);
                }
            // }
        }
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
