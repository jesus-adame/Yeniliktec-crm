<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'folio' => Str::random(6),
            'user_id' => auth()->user()->id,
            'client_id' => Contact::where('email', $this->contact_email)->first()->id ?? null,
            'lead_id' => $this->lead ?? null,
            'status' => 'active',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folio' => 'required',
            'user_id' => 'required',
            'client_id' => 'required',
            'lead_id' => 'nullable',
            'status' => 'required',
        ];
    }
}
