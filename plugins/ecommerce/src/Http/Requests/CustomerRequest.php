<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'required',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|unique:Plugin\Ecommerce\Models\Customers,phone,' . $request->id,
            'email' => 'required|email|unique:Plugin\Ecommerce\Models\Customers,email,' . $request->id
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => translate('Name is required', session()->get('api_locale')),
            'password.required' => translate('Password is required', session()->get('api_locale')),
            'password.confirmed' => translate('Password does not match', session()->get('api_locale')),
            'password.min' => translate('Password is too short', session()->get('api_locale')),
            'phone.required' => translate('Phone is required', session()->get('api_locale')),
            'phone.unique' => translate('Phone is already used', session()->get('api_locale')),
            'email.required' => translate('Email is required', session()->get('api_locale')),
            'email.email' => translate('Incorrect email', session()->get('api_locale')),
            'email.unique' => translate('Email is already used', session()->get('api_locale')),
        ];
    }
}