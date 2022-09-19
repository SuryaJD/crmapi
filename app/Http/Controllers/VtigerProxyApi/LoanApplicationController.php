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

            {
                "success": true,
                "documents": [
                    "http://localhost:3333/api/loan/view-document/1662462487947/Legality-1",
                    {
                            "loan_documents": [{
                                "GST_CERTIFICATE": "http://localhost:3333/api/loan/view-document/1662462487947/GST_CERTIFICATE"
                            },
                            {
                                "UDHYAM": "http://localhost:3333/api/loan/view-document/1662462487947/UDHYAM"
                            },
                            {
                                "ELECTRICITY_BILL_REGISTERED_ADDRESS": "http://localhost:3333/api/loan/view-document/1662462487947/ELECTRICITY_BILL_REGISTERED_ADDRESS"
                            },
                            {
                                "ELECTRICITY_BILL_INSTALLATION_SITE": "http://localhost:3333/api/loan/view-document/1662462487947/ELECTRICITY_BILL_INSTALLATION_SITE"
                            },
                            {
                                "OWNERSHIP_PROOF": "http://localhost:3333/api/loan/view-document/1662462487947/OWNERSHIP_PROOF"
                            },
                            {
                                "PARTNERSHIP_FIRM_PAN": "http://localhost:3333/api/loan/view-document/1662462487947/PARTNERSHIP_FIRM_PAN"
                            },
                            {
                                "PARTNERSHIP_FIRM_PARTNERSHIP_DEED": "http://localhost:3333/api/loan/view-document/1662462487947/PARTNERSHIP_FIRM_PARTNERSHIP_DEED"
                            },
                            {
                                "PRIVATE_OR_PUBLIC_PAN": "http://localhost:3333/api/loan/view-document/1662462487947/PRIVATE_OR_PUBLIC_PAN"
                            },
                            {
                                "PRIVATE_OR_PUBLIC_DIRECTORS_LIST": "http://localhost:3333/api/loan/view-document/1662462487947/PRIVATE_OR_PUBLIC_DIRECTORS_LIST"
                            },
                            {
                                "PRIVATE_OR_PUBLIC_COI_OR_MOA_OR_AOA": "http://localhost:3333/api/loan/view-document/1662462487947/PRIVATE_OR_PUBLIC_COI_OR_MOA_OR_AOA"
                            },
                            {
                                "INCOME_DOCUMENT_FINANCIAL_STATEMENT": "http://localhost:3333/api/loan/view-document/1662462487947/INCOME_DOCUMENT_FINANCIAL_STATEMENT"
                            },
                            {
                                "INCOME_DOCUMENT_BANK_STATEMENT": "http://localhost:3333/api/loan/view-document/1662462487947/INCOME_DOCUMENT_BANK_STATEMENT"
                            },
                            {
                                "INCOME_DOCUMENT_GST_RETURNS": "http://localhost:3333/api/loan/view-document/1662462487947/INCOME_DOCUMENT_GST_RETURNS"
                            },
                            {
                                "INCOME_DOCUMENT_LOAN_OBLIGATION_SHEET": "http://localhost:3333/api/loan/view-document/1662462487947/INCOME_DOCUMENT_LOAN_OBLIGATION_SHEET"
                            },
                            {
                                "INCOME_DOCUMENT_SANCTION_LETTER": "http://localhost:3333/api/loan/view-document/1662462487947/INCOME_DOCUMENT_SANCTION_LETTER"
                            },
                            {
                                "INCOME_DOCUMENT_SIGNED_PROPOSAL": "http://localhost:3333/api/loan/view-document/1662462487947/INCOME_DOCUMENT_SIGNED_PROPOSAL"
                            }
                        ],
                        "applicants_documents": [{
                                "ID_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/40/ID_PROOF",
                                "AADHAR_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/40/AADHAR_PROOF",
                                "AGE_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/40/AGE_PROOF",
                                "SIGN_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/40/SIGN_PROOF"
                            },
                            {
                                "ID_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/41/ID_PROOF",
                                "AADHAR_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/41/AADHAR_PROOF",
                                "AGE_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/41/AGE_PROOF",
                                "SIGN_PROOF": "http://localhost:3333/api/loan/applicant/view-document/1662462487947/41/SIGN_PROOF"
                            }
                        ]
                    }
                ]
            }

            $formData =  [
                'loanapplication_tks_loanapplic' => $data['id'], // Loan Application ID - Primary Key In Aerem
                'cf_997'                         => $data['documents'][0], // Signed URLs
                'cf_1023'                        => $data['documents'][1]['loan_documents'][0]['GST_CERTIFICATE'],
                'cf_1025'                        => $data['documents'][1]['loan_documents'][1]['UDHYAM'],
                'cf_1027'                        => $data['documents'][1]['loan_documents'][2]['ELECTRICITY_BILL_REGISTERED_ADDRESS'],
                'cf_1029'                        => $data['documents'][1]['loan_documents'][3]['ELECTRICITY_BILL_INSTALLATION_SITE'],
                'cf_1031'                        => $data['documents'][1]['loan_documents'][4]['OWNERSHIP_PROOF'],
                'cf_1033'                        => $data['documents'][1]['loan_documents'][5]['PARTNERSHIP_FIRM_PAN'],
                'cf_1035'                        => $data['documents'][1]['loan_documents'][6]['PARTNERSHIP_FIRM_PARTNERSHIP_DEED'],
                'cf_1037'                        => $data['documents'][1]['loan_documents'][7]['PRIVATE_OR_PUBLIC_PAN'],
                'cf_1039'                        => $data['documents'][1]['loan_documents'][8]['PRIVATE_OR_PUBLIC_DIRECTORS_LIST'],
                'cf_1041'                        => $data['documents'][1]['loan_documents'][9]['PRIVATE_OR_PUBLIC_COI_OR_MOA_OR_AOA'],
                'cf_1043'                        => $data['documents'][1]['loan_documents'][10]['INCOME_DOCUMENT_FINANCIAL_STATEMENT'],
                'cf_1045'                        => $data['documents'][1]['loan_documents'][11]['INCOME_DOCUMENT_BANK_STATEMENT'],
                'cf_1047'                        => $data['documents'][1]['loan_documents'][12]['INCOME_DOCUMENT_GST_RETURNS'],
                'cf_1049'                        => $data['documents'][1]['loan_documents'][13]['INCOME_DOCUMENT_LOAN_OBLIGATION_SHEET'],
                'cf_1051'                        => $data['documents'][1]['loan_documents'][14]['INCOME_DOCUMENT_SANCTION_LETTER'],
                'cf_1053'                        => $data['documents'][1]['loan_documents'][15]['INCOME_DOCUMENT_SIGNED_PROPOSAL'],
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

        Log::debug("check",[
            'id' => $request->get('loanapplication_tks_loanapplic'),
            'loanApplication' => $loanApplication,
            'milestones' => $milestones,
        ]);



        $tranches = [];

        $byTranch = collect($milestones)->groupBy('milestone_tks_tranch')->toArray();


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

        Log::error('tranches',$tranches);
        Log::error('byTranch',$byTranch);



        $reponse = [
            "loan_application_id" => $request->get('loanapplication_tks_loanapplic'),
            "status" => "APPROVED",
            'tranches' => $tranches
        ];



        return json_encode($reponse);
    }

    public function camStatusChanged(Request $request)
    {
        $reponse = [
            "loan_application_id" => $request->get('loanapplication_tks_loanapplic'),
            "status" => $request->get('cf_995'),
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
