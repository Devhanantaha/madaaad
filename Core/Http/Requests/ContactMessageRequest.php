<?php

namespace Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|email',
            'name' => 'required|max:250',
            'message' => 'required|max:1000',
            'subject' => 'required|max:250',
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
            'email.required' => translate('Email is required'),
            'email.email' => translate('Incorrect email'),
            'name.required' => translate('Name is required'),
            'message.required' => translate('Message is required'),
            'subject.required' => translate('Subject is required'),
        ];
    }
}