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
            'phone_number',
            'type',
            'status',
            'description',
        ]);

        $emailExist = Contact::where('email', $request->email)->get();

        if (count($emailExist)) {
            $formData['name'] = $request->name;
            $formData['last_name'] = $request->last_name;

            $emailExist[0]->updated($formData);
            return response(['message' => 'Contacto actualizado correctamente.']);
        }

        $contact = Contact::updateOrCreate([
            'name' => $request->name,
            'last_name' => $request->last_name,
        ], $formData);

        $lead->contact_id = $contact->id;
        $lead->save();

        return response(['message' => 'Contacto actualizado correctamente.']);
    }
}
