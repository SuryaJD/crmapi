<?php

namespace App\Http\Controllers\VtigerProxyApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Salaros\Vtiger\VTWSCLib\WSClient;

class LoanApplicationController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $notification = json_decode($request->getContent(), true);

        if (isset($notification['MessageAttributes']) && $notification['MessageAttributes']['Application']['Value'] == 'aerem') {

            Log::info('notification', $notification);

            $data = json_decode($notification['Message'], true);

            $formData =  [
                'loanapplication_tks_loanapplic' => $data['id'], // Loan Application ID - Primary Key In Aerem
                'cf_997'                         => $data['documents'][0], // Signed URLs
            ];

            Log::info("message", $data);

            $client = new WSClient('https://crm.aerem.co/', 'admin', 'Pt6Oh5A3YhofNY3');

            try {
                $added = $client->entities->createOne('Loanapplication', $formData);
                Log::info('Loan Application Added', $added);
            } catch (\Throwable $th) {
                Log::error('vtiger webservice error', [
                    'error' => $th->getMessage(),
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $client = new WSClient('https://crm.aerem.co/', 'admin', 'Pt6Oh5A3YhofNY3');

        $loanApplication = $client->entities->findOne('Loanapplication', [
            'loanapplication_tks_loanapplic'  => $request->get('loanapplication_tks_loanapplic') //$request->get('loanapplication_tks_loanapplic'),
        ]);

        $milestones = $client->invokeOperation('retrieve_related', [
            'id'    => $loanApplication['id'],
            'relatedType'  => 'Milestone',
            'relatedLabel'  => 'Milestone'
        ], 'GET');



        $byTranch = collect($milestones)->groupBy('milestone_tks_tranch')->toArray();

        $tranches = [];

        if (isset($byTranch['Tranch One'])) {
            array_push($tranches,[
                'milestones' =>
                collect($byTranch['Tranch One'])->map(function ($milestone) {
                    return [
                        'description' => $milestone['milestone_tks_defination'],
                    ];
                }),

            ]);
        }

        if (isset($byTranch['Tranch Two'])) {
            array_push($tranches,[
                'milestones' =>
                collect($byTranch['Tranch Two'])->map(function ($milestone) {
                    return [
                        'description' => $milestone['milestone_tks_defination'],
                    ];
                }),

            ]);
        }

        if (isset($byTranch['Tranch Three'])) {
            array_push($tranches,[
                'milestones' =>
                collect($byTranch['Tranch Three'])->map(function ($milestone) {
                    return [
                        'description' => $milestone['milestone_tks_defination'],
                    ];
                }),

            ]);
        }

        Log::debug('data',$tranches);

        $reponse = [
            "loan_application_id" => $request->get('loanapplication_tks_loanapplic'),
            "status" => "APPROVED",
            'tranches' => $tranches
        ];

        return json_encode($reponse);
    }

    public function camApproved(Request $request)
    {
        $reponse = [
            "loan_application_id" => $request->get('loanapplication_tks_loanapplic'),
            "status" => "APPROVED",
        ];

        return json_encode($reponse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
