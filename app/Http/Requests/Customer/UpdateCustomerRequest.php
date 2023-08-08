<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user != null && $user->tokenCan('customer:update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array 
    {
        $method = $this->method();

        if($method == 'PUT') {
            return [
                'name' => ['required'],
                'type' => ['required', Rule::in(['I', 'i', 'B', 'b'])],
                'email' => ['required', 'email'],
                'address' => ['required'],
                'city' => ['required'],
                'state' => ['required'],
                'postal_code' => ['required'],
            ];
        }

        return [
            'name' => ['sometimes', 'required'],
            'type' => ['sometimes', 'required', Rule::in(['I', 'i', 'B', 'b'])],
            'email' => ['sometimes', 'required', 'email'],
            'address' => ['sometimes', 'required'],
            'city' => ['sometimes', 'required'],
            'state' => ['sometimes', 'required'],
            'postal_code' => ['sometimes', 'required'],
        ];
    }

    // protected function prepareForValidation()
    // {
    //     // if($this->postalCode) {

    //         $this->merge([
    //             'postal_code' => $this->postalCode
    //         ]);
    //     // }
    // }
}
