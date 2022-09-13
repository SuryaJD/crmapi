<?php

namespace App\Http\Controllers\Invoker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\VtigerProxyApi\LeadController;
use App\Http\Controllers\VtigerProxyApi\LoanApplicationController;
use App\Http\Controllers\VtigerProxyApi\TranchController;

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

            if (isset($notification['MessageAttributes']['Module']['Value']) && $notification['MessageAttributes']['Module']['Value'] == 'LoanApplication') {
                (new LoanApplicationController)->create($request);
            } elseif (isset($notification['MessageAttributes']['Module']['Value']) && $notification['MessageAttributes']['Module']['Value'] == 'Leads') {
                if (isset($data['isResubmit']) && $data['isResubmit']) {
                    (new LeadController)->update($request);
                } else {
                    (new LeadController)->store($request);
                }
            }elseif (isset($notification['MessageAttributes']['Module']['Value']) && $notification['MessageAttributes']['Module']['Value'] == 'Tranch') {
                    (new TranchController)->update($request);
            }
        } else {
            abort(403);
        }
    }
}
