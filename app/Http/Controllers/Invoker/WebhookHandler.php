<?php

namespace App\Http\Controllers\Invoker;

use Aws\Sns\SnsClient;
use Illuminate\Http\Request;
use Aws\Credentials\Credentials;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notifications\webhook\NotifyToSns;
use Illuminate\Support\Facades\Notification;

class WebhookHandler extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $client = new SnsClient([
            'version' => '2010-03-31',
            'region' => config('services.sns.region'),
            'credentials' => new Credentials(
                config('services.sns.key'),
                config('services.sns.secret')
            )
        ]);


        Log::info($request->all());

        $subject = 'You got a new SNS Message';

        $message = json_encode([
            "entity_name" => $request->lastname,
            "entity_email" => $request->cf_872,
            "entity_phone" => $request->cf_896,
            "entity_address_l1" => $request->cf_862,
            "entity_address_l2" => $request->cf_864,
            "entity_pin" => $request->cf_900 ,
            "entity_dist" => $request->cf_868,
            "entity_state" => $request->state,
            "entity_country" => 'India',
            "site_address_l1" => $request->cf_876,
            "site_address_l2" => $request->cf_878,
            "site_pin"  => $request->cf_898,
            "site_dist" => $request->cf_882,
            "site_state" => $request->cf_884,
            "site_country" => 'India',
            "installer_lead_id" => $request->cf_894,
            "status" => $request->cf_886,
            "remarks" => $request->cf_888,
            "proposed_project_size_kw" => $request->cf_874,
            "installer_id" => $request->cf_912,
            "installer_name" => $request->cf_918,
            "installer_email" => $request->cf_914,
            "installer_contact" => $request->cf_916
            // "electric_bill" => $request->cf_908,
            // "proposal"      => $request->cf_910,
        ]);

        // Below data In Id +
        // "entity_name": "hello1",
        // "entity_email": "entityEmail@email.com",
        // "entity_phone": "7831940099",
        // "entity_address_l1": "entityAddressL11",
        // "entity_address_l2": "entity addres L2",
        // "entity_pin": "123456",
        // "entity_dist": "entityDist1",
        // "entity_state": "entityState1",
        // "entity_country": "entityCountry1",
        // "electric_bill": "electricbill",
        // "proposal": "proposal",
        // "site_address_l1": "siteAddressL11",
        // "site_address_l2": "siteAddressL21",
        // "site_pin": "654321",
        // "site_dist": "siteDist1",
        // "site_state": "siteState1",
        // "site_country": "siteCountry1",
        // "proposed_project_size_kw": "`123",
        // "proposed_project_area_sqmtr": "321"

        try {
            $client->publish([
                'TopicArn' => config('services.sns.arn'),
                'Message' => $message,
                'Subject' => $subject,
                'MessageAttributes' => [
                    'Application' => [
                        'DataType'    => 'String',
                        'StringValue' => 'vtiger'
                    ],
                    'Module'       => [
                        'DataType'    => 'String',
                        'StringValue' => 'Leads'
                    ],
                    'Trigger'     => [
                        'DataType'    => 'String',
                        'StringValue' => 'Leads.updated'
                    ]
                    // 'Module'      => 'Leads',
                    // 'Trigger'     => 'lead.updated',
                    // 'Task'        => 'Leads Status Updated',
                ]
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

    }
}
