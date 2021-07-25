<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EditLeadRequest extends FormRequest
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
            'user_id'     => 'required|exists:users,id',
            'agent_id'    => 'required|exists:users,id',
            'contact_id'  => 'nullable',
            'title'       => 'required|min:4|max:100',
            'author'      => 'required|email|exists:users,email|max:200',
            'agent'       => 'required|email|exists:users,email|max:200',
            'status'      => 'required|string|max:10',
            'description' => 'required|min:4|max:500',
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
            'user_id' => User::where('email', $this->author)->first()->id ?? auth()->user()->id,
            'agent_id' => User::where('email', $this->agent)->first()->id ?? null,
        ]);
    }
}
