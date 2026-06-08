<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerBasicUpdateRequest extends FormRequest
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
            'phone' => 'required|unique:tl_com_customers,phone,' . auth('jwt-customer')->user()->id,
            'image' => 'nullable|max:2000|mimes:jpeg,png,jpg'
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
            'phone.required' => translate('Phone is required', session()->get('api_locale')),
            'phone.unique' => translate('Phone is already used', session()->get('api_locale')),
            'image.max' => translate('Image size is too large', session()->get('api_locale')),
            'image.mimes' => translate('Invalid image format', session()->get('api_locale')),
        ];
    }
}
