<?php

namespace App\Http\Requests;

use App\Rules\FullLastNameRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrEditContactRequest extends FormRequest
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
            'name'            => ['required', 'min:4', 'max:25', new FullLastNameRule],
            'last_name'       => ['required', 'min:4', 'max:25', new FullLastNameRule],
            'email' => [
                'required_without:phone_number',
                'nullable',
                'email'
            ],
            'phone_number' => [
                'required_without:email',
                'nullable',
                'numeric',
                'digits_between:10,12',
            ],
            'type'            => 'required|min:4|max:25',
            'status'          => 'required|min:1|max:10',
            'description'     => 'nullable|min:4|max:255',
            // 'billing_name'    => 'required|min:4|max:25',
            // 'billing_code'    => 'nullable|min:4|max:10',
            // 'billing_address' => 'nullable|min:4|max:100',
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
            'type' => 'customer',
            'status' => 'active',
            // 'billing_name' => $this->name . ' ' . $this->last_name,
            // 'billing_code' => null,
            // 'billing_address' => null,
        ]);
    }
}
