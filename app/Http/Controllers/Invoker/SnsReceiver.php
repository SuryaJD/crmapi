<?php

namespace App\Http\Controllers\Invoker;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VtigerProxyApi\LeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SnsReceiver extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->getIntention($request);
    }

    public function getIntention($request)
    {
        $notification = json_decode($request->getContent(), true);
        if (isset($notification['MessageAttributes']) && $notification['MessageAttributes']['Application']['Value'] == 'aerem') {
            $data = json_decode($notification['Message'], true);

            if(isset($data['isResubmited']) && $data['isResubmited']){
                (new LeadController)->update($request);

            }else {
                (new LeadController)->store($request);
            }
        }else{
                abort(403);
        }
    }
}
