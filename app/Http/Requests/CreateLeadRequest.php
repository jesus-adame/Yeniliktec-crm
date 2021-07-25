<?php

namespace App\Http\Requests;

use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'     => 'nullable|exists:users,id',
            'agent_id'    => 'nullable|exists:users,id',
            'contact_id'  => 'nullable',
            'column_id'   => 'nullable|exists:columns,id',
            'title'       => 'required|min:4|max:100',
            'author'      => 'required|email|exists:users,email|max:200',
            'agent'       => 'required|email|exists:users,email|max:200',
            'status'      => 'required|string|max:10',
            'description' => 'required|min:4|max:500',

            'contact_name'            => 'required|min:4|max:25',
            'contact_last_name'       => 'required|min:4|max:25',
            'contact_email'           => 'required|email',
            'contact_phone_number'    => 'required|numeric|digits_between:10,12',
            'contact_type'            => 'required|min:4|max:25',
            'contact_status'          => 'required|min:1|max:10',
            'contact_description'     => 'required|min:4|max:255',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
            'agent_id' => User::where('email', $this->author)->first()->id ?? null,
            'column_id' => Column::where('name', 'inbox')->first()->id ?? null,
            'contact_id' => null,

            'contact_type' => 'customer',
            'contact_status' => 'active',
        ]);
    }
}
