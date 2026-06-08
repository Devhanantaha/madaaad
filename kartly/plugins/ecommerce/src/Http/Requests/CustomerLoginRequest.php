<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerLoginRequest extends FormRequest
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
            'password' => 'required',
            'email' => 'required|email'
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
            'password.required' => translate('Password is required', session()->get('api_locale')),
            'email.required' => translate('Email is required', session()->get('api_locale')),
            'email.email' => translate('Incorrect email', session()->get('api_locale')),
        ];
    }
}