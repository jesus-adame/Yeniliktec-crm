<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrEditContactRequest;
use App\Models\Contact;
use App\Models\Lead;

class ContactController extends Controller
{
    public function createOrUpdateContactLead(CreateOrEditContactRequest $request, Lead $lead)
    {
        $formData = $request->only([
            'lead_id',
            'name',
            'last_name',
            'email',
            'phone_number',
            'type',
            'status',
            'description',
        ]);

        $contact = Contact::updateOrCreate(['email' => $request->email], $formData);

        $lead->contact_id = $contact->id;
        $lead->save();

        return response(['message' => 'Contacto actualizado correctamente.']);
    }
}
