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

        $message = json_encode($request->all());

        $client->publish([
            'TopicArn' => 'arn:aws:sns:ap-south-1:838923037017:model_sync',
            'Message' => $message,
            'Subject' => $subject
        ]);
    }
}
