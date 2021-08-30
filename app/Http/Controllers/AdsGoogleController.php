<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoogleLeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Models\Column;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdsGoogleController extends Controller
{
    protected $google_key = '30D6E83F394734DED7A37C8E4F8C3709FD18D922';

    protected $validationRules = [
        'user_column_data' => 'required',
        'google_key' => 'required',
    ];

    public function generateLead(StoreGoogleLeadRequest $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $givenData = $request->only([
            'campaign_id',
            'google_key',
            'user_column_data',
        ]);

        $columns = collect($givenData['user_column_data']);

        if ($givenData['google_key'] != $this->google_key) {
            return response([
                'message' => 'Forbidden',
            ], 403);
        }

        $agent = User::where('email', 'ventas@yeniliktec.com')->first();

        $contactData = [
            'name' => $columns->firstWhere('column_id', 'FIRST_NAME')['string_value'],
            'last_name' => $columns->firstWhere('column_id', 'LAST_NAME')['string_value'],
            'email' => $columns->firstWhere('column_id', 'EMAIL')['string_value'],
            'phone_number' => $columns->firstWhere('column_id', 'PHONE_NUMBER')['string_value'],
            'type' => 'customer',
            'status' => 'active',
            'billing_name' => $columns->firstWhere('column_id', 'COMPANY_NAME')['string_value'],
        ];

        $leadDescription = $givenData['company_name'] . ' '
            . $givenData['company_size']
            . ' Campaign ID: '
            . $givenData['campaign_id'];

        $leadData = [
            'user_id' => $agent->id,
            'agent_id' => $agent->id,
            'column_id' => Column::where('slug', 'inbox')->first()->id,
            'title' => $givenData['name'] . ' ' . $givenData['last_name'],
            'description' => $leadDescription,
            'status' => 'pending',
        ];

        DB::transaction(function () use ($leadData, $contactData) {
            $lead = Lead::create($leadData);

            $contact = Contact::where('email', $contactData['email'])->first();

            if ($contact) {
                $contact->update($contactData);
            } else {
                $contact = Contact::create($contactData);
            }
    
            $lead->contact_id = $contact->id;
            $lead->save();
        });
        
        return response([
            'message' => 'Lead creado correctamente',
            'data' =>  $givenData,
        ]);
    }
}
