<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Column;
use App\Models\Contact;
use App\Services\PhpImapAdapter;

class MailLeadsController extends Controller
{
    public function createLead($messageId)
    {
        $account = request()->input('account');

        if ($account == 'ventas') {
            $account = 'default';
        }

        $client = new PhpImapAdapter($account);

        $message = $client->showMessage($messageId);

        $personal = explode(' ', $message['from']['personal']);

        $contactData = [
            'name' => $personal[0],
            'last_name' => str_replace($personal[0], '', $message['from']['personal']),
            'email' => $message['from']['mail'],
            'phone_number' => '',
            'type' => 'customer',
            'status' => 'active',
        ];

        $leadData = [
            'user_id' => auth()->user()->id,
            'agent_id' => auth()->user()->id,
            'column_id' => Column::where('slug', 'inbox')->first()->id ?? null,
            'title' => $message['subject'],
            'description' => $message['body_plain'] ?? $message['subject'],
            'status' => 'pending',
        ];

        try {
            $contact = Contact::where('name', $contactData['name'])
                ->where('last_name', $contactData['last_name'])
                ->first();
            
            if ($contact) {
                $contact->update($contactData);
            } else {
                $contact = Contact::create($contactData);
            }

            $leadData['contact_id'] = $contact->id;

            Lead::create($leadData);

            return response([
                'message' => 'Registrado correctamente'
            ]);

        } catch (\Throwable $th) {
            logs('local')->info($th->getMessage());

            return response([
                'message' => 'Ocurrió un error',
            ], 500);
        }
    }
}
