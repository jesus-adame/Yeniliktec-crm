<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeadRequest;
use App\Http\Requests\EditLeadRequest;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Lead;
use Illuminate\Http\Request;
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
                'documents',
                'quotes' => function ($query) {
                    $query
                    ->select('quotes.*')
                    ->selectTotal()
                    ->with(['contact', 'items' => function ($query) {
                        $query->with('product');
                    }]);
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
            'name'          => $request->contact_name,
            'last_name'     => $request->contact_last_name,
            'email'         => $request->contact_email,
            'phone_number'  => $request->contact_phone_number,
            'type'          => $request->contact_type,
            'status'        => $request->contact_status,
            'description'   => $request->contact_description,
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

    public function addDocument(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'required',
            'document' => 'required|max:20000|mimes:doc,docx,pdf,jpg,png',
        ]);

        $data = $request->only(['name']);
        $filePath = $request->file('document')->store('documents');

        try {
            DB::beginTransaction();

            $document = Document::create([
                'name' => $data['name'],
                'path' => $filePath,
                'mime_type' =>$request->file('document')->getMimeType(),
            ]);
    
            $lead->documents()->attach($document->id);
            DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            logs('local')->error($th->getMessage());
            abort(500, 'Server error');
        }

        return response(['message' => 'Agregado correctamente']);
    }
}
