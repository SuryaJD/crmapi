<?php

namespace App\Http\Controllers\Invoker;

use Aws\Sns\SnsClient;
use Illuminate\Http\Request;
use Aws\Credentials\Credentials;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VtigerProxyApi\LoanApplicationController;
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

        if ($request->has('module') && $request->get('module') == 'lead') {
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
            ]);

            $Module = 'Leads';
            $trigger = 'Leads.updated';

        }elseif ($request->has('module')
            && $request->get('module') == 'loan_application'
            && $request->has('event')
            && $request->get('event') == 'loanapplication.approved'
        ){
            $message = (new LoanApplicationController)->show($request);
            $Module = 'Milestones';
            $trigger = 'Milestones.updated';
        }elseif ($request->has('module')
            && $request->get('module') == 'loan_application'
            && $request->has('event')
            && $request->get('event') == 'loanapplication.camApproved'
            ) {
            $message = (new LoanApplicationController)->camApproved($request);
            $Module = 'Milestones';
            $trigger = 'Milestones.updated';
        }

        $subject = 'You got a new SNS Message';


        Log::error('loan_application',[
            $message
        ]);

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
                        'StringValue' => $Module
                    ],
                    'Trigger'     => [
                        'DataType'    => 'String',
                        'StringValue' => $trigger,
                    ],
                ]
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

    }
}
