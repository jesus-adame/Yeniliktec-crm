<?php

namespace App\Http\Controllers;

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
        'name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'phone_number' => 'required',
        'company_name' => 'required',
        'company_size' => 'required',
        'campaign_id' => 'required',
        'google_key' => 'required',
    ];

    public function generateLead(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors(),
            ]);
        }
        
        $givenData = $request->only([
            'name',
            'last_name',
            'email',
            'phone_number',
            'company_name',
            'company_size',
            'campaign_id',
            'google_key',
        ]);

        if ($givenData['google_key'] != $this->google_key) {
            return response([
                'message' => 'Forbidden',
            ], 403);
        }

        $agent = User::where('email', 'ventas@yeniliktec.com')->first();

        $contactData = [
            'name' => $givenData['name'],
            'last_name' => $givenData['last_name'],
            'email' => $givenData['email'],
            'phone_number' => $givenData['phone_number'],
            'type' => 'customer',
            'status' => 'active',
            'billing_name' => $givenData['company_name'],
        ];

        $leadData = [
            'user_id' => $agent->id,
            'agent_id' => $agent->id,
            'column_id' => Column::where('slug', 'inbox')->first()->id,
            'title' => $givenData['name'] . ' ' . $givenData['last_name'],
            'description' => $givenData['company_name'] . ' ' . $givenData['company_size'] . ' Campaign ID: ' . $givenData['campaign_id'],
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