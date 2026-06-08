<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerAddressRequest extends FormRequest
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

        $rules=[];
        if (getEcommerceSetting('enable_country_state_city_in_checkout') == config('settings.general_status.active')) {
                $rules['name'] = getEcommerceSetting('name_required_in_checkout') == config('settings.general_status.active') ? 'required' : 'nullable';
                $rules['phone'] = getEcommerceSetting('phone_required_in_checkout') == config('settings.general_status.active') ? 'required' : 'nullable';
                $rules['address'] = getEcommerceSetting('address_required_in_checkout') == config('settings.general_status.active') ? 'required' : 'nullable';
                $rules['postal_code'] = getEcommerceSetting('post_code_required_in_checkout') == config('settings.general_status.active') ? 'required' : 'nullable';
           
        }

        if (getEcommerceSetting('enable_country_state_city_in_checkout') == config('settings.general_status.active')) {
                $rules['country'] = 'required|exists:Plugin\Ecommerce\Models\Country,id';
                $rules['state'] = 'required|exists:Plugin\Ecommerce\Models\States,id';
                $rules['city'] = 'required|exists:Plugin\Ecommerce\Models\Cities,id';
        }

        return $rules;
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
            'address.required' => translate('Address is required', session()->get('api_locale')),
            'country.required' => translate('Country is required', session()->get('api_locale')),
            'state.required' => translate('State is required', session()->get('api_locale')),
            'city.required' => translate('City is required', session()->get('api_locale')),
            'country.exists' => translate('Country is invalid', session()->get('api_locale')),
            'state.exists' => translate('State is invalid', session()->get('api_locale')),
            'city.exists' => translate('City is invalid', session()->get('api_locale')),
            'postal.required' => translate('Postal code is required', session()->get('api_locale')),
        ];
    }
}