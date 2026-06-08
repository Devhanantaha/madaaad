<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GuestCheckoutRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->has('products')) {
            $this->merge([
                'products' => json_decode($this->input('products'), true)
            ]);
        }
        if ($this->has('shipping_address')) {
            $this->merge([
                'shipping_address' => json_decode($this->input('shipping_address'), true)
            ]);
        }
        if ($this->has('billing_address')) {
            $this->merge([
                'billing_address' => json_decode($this->input('billing_address'), true)
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {

        $rules = [];
        $rules['origin'] = 'required';
        //Bank Payment validation
        if ($request['payment_id'] == config('ecommerce.payment_methods.bank')) {
            $rules['bank_name'] = 'required|max:100';
            $rules['branch_name'] = 'required|max:100';
            $rules['account_number'] = 'required|max:100';
            $rules['account_name'] = 'required|max:100';
            $rules['transaction_number'] = 'required|max:100';
            $rules['receipt'] = 'required|mimes:jpeg,png,jpg.pdf';
        }


        if ($request->has('create_new_account')) {
            $rules['password'] = 'required|confirmed|min:6|max:20';
        }

        //Pickup Point Validation
        if ($request->has('pickup_point')) {
            $rules['pickup_point'] = 'required|exists:Plugin\PickupPoint\Models\PickupPoint,id';
            $rules['customer_email'] = 'required|email|max:100';
            $rules['customer_name'] = 'required|max:100';
        }

        //Shipping Address Validation
        if (!$request->has('pickup_point')) {

            if (getEcommerceSetting('enable_name_in_checkout') == 1) {
                $rules['shipping_address.name'] =
                    (getEcommerceSetting('name_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100';
            }

            if (getEcommerceSetting('enable_email_in_checkout') == 1) {
                $rules['shipping_address.email'] =
                    (getEcommerceSetting('email_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100|email';
            }

            if (getEcommerceSetting('enable_phone_in_checkout') == 1) {
                $rules['shipping_address.phone'] =
                    (getEcommerceSetting('phone_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100';
            }

            if (getEcommerceSetting('enable_address_in_checkout') == 1) {
                $rules['shipping_address.address'] =
                    (getEcommerceSetting('address_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:200';
            }

            if (getEcommerceSetting('enable_post_code_in_checkout') == 1) {
                $rules['shipping_address.postal_code'] =
                    (getEcommerceSetting('post_code_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100';
            }

            if (getEcommerceSetting('enable_country_state_city_in_checkout') == 1) {
                $rules['shipping_address.city_id'] = 'required';
                $rules['shipping_address.state_id'] = 'required';
                $rules['shipping_address.country_id'] = 'required';
            }
        }

        //Billing Address Validation
        if ($request->has('billing_address')) {
            if (getEcommerceSetting('enable_name_in_checkout') == 1) {
                $rules['billing_address.name'] =
                    (getEcommerceSetting('name_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100';
            }

            if (getEcommerceSetting('enable_email_in_checkout') == 1) {
                $rules['billing_address.email'] =
                    (getEcommerceSetting('email_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100|email';
            }

            if (getEcommerceSetting('enable_phone_in_checkout') == 1) {
                $rules['billing_address.phone'] =
                    (getEcommerceSetting('phone_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100';
            }

            if (getEcommerceSetting('enable_address_in_checkout') == 1) {
                $rules['billing_address.address'] =
                    (getEcommerceSetting('address_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:200';
            }

            if (getEcommerceSetting('enable_post_code_in_checkout') == 1) {
                $rules['billing_address.postal_code'] =
                    (getEcommerceSetting('post_code_required_in_checkout') == 1 ? 'required' : 'nullable') .
                    '|max:100';
            }

            if (getEcommerceSetting('enable_country_state_city_in_checkout') == 1) {
                $rules['billing_address.city_id'] = 'required';
                $rules['billing_address.state_id'] = 'required';
                $rules['billing_address.country_id'] = 'required';
            }
        }

        $rules['products'] = 'required|array';
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
            'customer_email.required' => translate('Email is required', session()->get('api_locale')),
            'customer_email.email' => translate('Incorrect email', session()->get('api_locale')),
            'customer_name.required' => translate('Name is required', session()->get('api_locale')),
            'password.confirmed' => translate('Password does not match', session()->get('api_locale')),
            'password.min' => translate('Minimum length is 6', session()->get('api_locale')),
            'password.max' => translate('Maximum length is 20', session()->get('api_locale')),
            'products.required' => translate('No products available for checkout', session()->get('api_locale')),

            'shipping_address.name.required' => translate('Name is required', session()->get('api_locale')),
            'shipping_address.email.required' => translate('Email is required', session()->get('api_locale')),
            'shipping_address.email.email' => translate('Incorrect email', session()->get('api_locale')),
            'shipping_address.email.unique' => translate('Email is already used', session()->get('api_locale')),
            'shipping_address.phone.required' => translate('Phone is required', session()->get('api_locale')),
            'shipping_address.address.required' => translate('Address is required', session()->get('api_locale')),
            'shipping_address.postal_code.required' => translate('Postal code is required', session()->get('api_locale')),
            'shipping_address.city_id.required' => translate('City is required', session()->get('api_locale')),
            'shipping_address.state_id.required' => translate('State is required', session()->get('api_locale')),
            'shipping_address.country_id.required' => translate('Country is required', session()->get('api_locale')),

            'billing_address.name.required' => translate('Name is required', session()->get('api_locale')),
            'billing_address.email.required' => translate('Email is required', session()->get('api_locale')),
            'billing_address.email.email' => translate('Incorrect email', session()->get('api_locale')),
            'billing_address.email.unique' => translate('Email is already used', session()->get('api_locale')),
            'billing_address.phone.required' => translate('Phone is required', session()->get('api_locale')),
            'billing_address.address.required' => translate('Address is required', session()->get('api_locale')),
            'billing_address.postal_code.required' => translate('Postal code is required', session()->get('api_locale')),
            'billing_address.city_id.required' => translate('City is required', session()->get('api_locale')),
            'billing_address.state_id.required' => translate('State is required', session()->get('api_locale')),
            'billing_address.country_id.required' => translate('Country is required', session()->get('api_locale')),

            'bank_name.required' => translate('Bank name is required', session()->get('api_locale')),
            'bank_name.max' => translate('Maximum length is 100', session()->get('api_locale')),

            'branch_name.required' => translate('Branch name is required', session()->get('api_locale')),
            'branch_name.max' => translate('Maximum length is 100', session()->get('api_locale')),

            'account_number.required' => translate('Account number is required', session()->get('api_locale')),
            'account_number.max' => translate('Maximum length is 100', session()->get('api_locale')),

            'account_name.required' => translate('Account name is required', session()->get('api_locale')),
            'account_name.max' => translate('Maximum length is 100', session()->get('api_locale')),

            'transaction_number.required' => translate('Transaction number is required', session()->get('api_locale')),
            'transaction_number.max' => translate('Maximum length is 100', session()->get('api_locale')),

            'receipt.required' => translate('Receipt is required', session()->get('api_locale')),
            'receipt.mimes' => translate('Receipt must be a file of type: jpeg, jpg, png, pdf', session()->get('api_locale')),
            'receipt.max' => translate('Receipt size must be less than 2MB', session()->get('api_locale')),

        ];
    }
}
