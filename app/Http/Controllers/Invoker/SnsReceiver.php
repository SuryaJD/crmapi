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
        (new LeadController)->store($request);
    }
}
