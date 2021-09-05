<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Column;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdsGoogleController extends Controller
{
    protected $validationRules = [
        'user_column_data' => 'required',
        'google_key' => 'required',
    ];

    /**
     * Register a new lead from a google ads campain
     */
    public function generateLead(Request $request)
    {
        try {
            $givenData = $this->validateRequest($request);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], $th->getCode());
        }
        
        DB::transaction(function () use ($givenData) {
            $columns = collect($givenData['user_column_data']);

            $contactData = $this->createContactData($columns);
            
            $contact = Contact::where('name', $contactData['name'])
                ->where('last_name', $contactData['last_name'])
                ->first();
            
            if ($contact) {
                $contact->update($contactData);
            } else {
                $contact = Contact::create($contactData);
            }
            
            Lead::create($this->createLeadData($columns, $contact->id, $givenData['campaign_id']));
        });
        
        return response(['message' => 'Lead creado correctamente']);
    }

    /**
     * Validate the request
     */
    private function validateRequest($request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->all()[0];
            throw new \Exception($errorMessage, 422);
        }
        
        $givenData = $request->only([
            'campaign_id',
            'google_key',
            'user_column_data',
        ]);

        if ($givenData['google_key'] != config('ads.google_key')) {
            throw new \Exception('Forbidden', 403);
        }

        return $givenData;
    }

    /**
     * Returns an formated contact data
     */
    private function createContactData(Collection $columns)
    {
        return [
            'name' => $columns->firstWhere('column_id', 'FIRST_NAME')['string_value'],
            'last_name' => $columns->firstWhere('column_id', 'LAST_NAME')['string_value'],
            'email' => $columns->firstWhere('column_id', 'EMAIL')['string_value'],
            'phone_number' => $columns->firstWhere('column_id', 'PHONE_NUMBER')['string_value'],
            'type' => 'customer',
            'status' => 'active',
            'billing_name' => $columns->firstWhere('column_id', 'COMPANY_NAME')['string_value'],
        ];
    }

    /**
     * Returns a formated lead data
     */
    private function createLeadData(Collection $columns, $contact_id, $campaign_id)
    {
        $agent = User::where('email', 'ventas@yeniliktec.com')->first();

        return [
            'user_id' => $agent->id,
            'agent_id' => $agent->id,
            'contact_id' => $contact_id,
            'column_id' => Column::where('slug', 'inbox')->first()->id,
            'title' => $this->getTitle($columns),
            'description' => $this->getDescription($columns, $campaign_id),
            'status' => 'pending',
        ];
    }

    private function getTitle(Collection $columns)
    {
        $first_name = $columns->firstWhere('column_id', 'FIRST_NAME')['string_value'];
        $last_name = $columns->firstWhere('column_id', 'LAST_NAME')['string_value'];
        return 'Google ADS - ' . $first_name . ' ' . $last_name;
    }

    private function getDescription(Collection $columns, $campaign_id)
    {
        return $columns->firstWhere('column_id', 'COMPANY_NAME')['string_value'] . ' '
        . $columns->firstWhere('column_id', 'COMPANY_SIZE')['string_value'] . '. Campaign ID: '
        . $campaign_id;
    }
}
