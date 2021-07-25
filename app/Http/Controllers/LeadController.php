<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeadRequest;
use App\Http\Requests\EditLeadRequest;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function show($id)
    {
        $lead = Lead::where('id', $id)
            ->with([
                'author' => function ($query) {
                    $query->withDefault();
                },
                'agent' => function ($query) {
                    $query->withDefault();
                },
                'contact' => function ($query) {
                    $query->withDefault();
                },
                'column' => function ($query) {
                    $query->withDefault();
                },
            ])
            ->first();

        return response($lead);
    }

    public function store(CreateLeadRequest $request)
    {
        $leadData = $request->only([
            'user_id',
            'contact_id',
            'agent_id',
            'column_id',
            'title',
            'description',
            'status',
        ]);
        
        $contactData = [
            'name' => $request->contact_name,
            'last_name' => $request->contact_last_name,
            'email' => $request->contact_email,
            'phone_number' => $request->contact_phone_number,
            'type' => $request->contact_type,
            'status' => $request->contact_status,
            'description' => $request->contact_description,
        ];

        DB::transaction(function () use ($leadData, $contactData) {
            $lead = Lead::create($leadData);
            $contact = Contact::updateOrCreate($contactData);
    
            $lead->contact_id = $contact->id;
            $lead->save();
        });

        return response(['message' => 'Registrado correctamente.']);
    }

    public function update(EditLeadRequest $request, Lead $lead)
    {
        $formData = $request->only([
            'user_id',
            'contact_id',
            'agent_id',
            'column_id',
            'title',
            'description',
            'status',
        ]);

        $lead->update($formData);

        return response(['message' => 'Actualizado correctamente.']);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return response(['message' => 'Eliminado correctamente.']);
    }
}
