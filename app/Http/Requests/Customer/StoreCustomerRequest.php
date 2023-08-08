<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //get the user
        //because user object has a method that will allow us to determine whether or not the token can do something
        $user = $this->user();

        return $user != null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in(['I', 'i', 'B', 'b'])],
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',

        ];
    }

    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'postal_code' => $this->postalCode
    //     ]);
    // }
}
