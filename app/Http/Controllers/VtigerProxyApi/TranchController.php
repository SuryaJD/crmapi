<?php

namespace App\Http\Controllers\VtigerProxyApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Salaros\Vtiger\VTWSCLib\WSClient;

class TranchController extends Controller
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
    public function create()
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
        //
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
    public function update(Request $request)
    {
        $client = new WSClient('https://crm.aerem.co/', 'admin', 'Pt6Oh5A3YhofNY3');

        $notification = json_decode($request->getContent(), true);
        $data = json_decode($notification['Message'], true);

        $milestones = $data['milestone_links'];

        $tranchId = $data['tranch']['id'];


        $loanApplicationId = $data['tranch']['loan_application_id'];

        $loanApplication = $client->entities->findOne('Loanapplication', [
            'loanapplication_tks_loanapplic'  => $loanApplicationId //$request->get('loanapplication_tks_loanapplic'),
        ]);

        $client->entities->updateOne('Leads', $loanApplication['id'],[
            'cf_1005' => $tranchId
        ]);

        $Relatedmilestones = $client->invokeOperation('retrieve_related', [
            'id'    => $loanApplication['id'],
            'relatedType'  => 'Milestone',
            'relatedLabel'  => 'Milestone'
        ], 'GET');

        Log::error('Relatedmilestones',$Relatedmilestones);


        foreach ($milestones as $milestone) {

            $changes = [];


            Log::debug('miles',$milestone);
            $hasToUpdated = collect($Relatedmilestones)->where('milestone_tks_defination',$milestone['description'])->first();

            Log::debug('has to be updated',$hasToUpdated);

            if ($hasToUpdated != null && is_array($milestone['links']) && !empty($milestone['links'])) {
                if (isset($milestone['links'][0])) {
                    $changes['milestone_tks_documentone'] = $milestone['links'][0];
                }elseif (isset($milestone['links'][1])) {
                    $changes['milestone_tks_documenttwo'] = $milestone['links'][1];
                }elseif (isset($milestone['links'][2])) {
                    $changes['milestone_tks_documentthree'] = $milestone['links'][2];
                }elseif (isset($milestone['links'][3])) {
                    $changes['milestone_tks_documentfour'] = $milestone['links'][3];
                }elseif (isset($milestone['links'][4])) {
                    $changes['milestone_tks_documentfive'] = $milestone['links'][4];
                }elseif (isset($milestone['links'][5])) {
                    $changes['milestone_tks_documentsix'] = $milestone['links'][5];
                }

                Log::debug('changes',[
                    'milestones' => $milestone['links'],
                    'changes'    => $changes
                ]);

                $client->entities->updateOne('Milestone', $hasToUpdated['id'], $changes);
                Log::info('Inside');
            }else{
                Log::info('Outside');
            }



            // Log::info('description',$milestone['links']);
            // Log::info('data',$milestoneFetched);
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
