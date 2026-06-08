<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerCheckoutRequest extends FormRequest
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
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {

        $rules = [];

        //Bank Payment validation
        if ($request['payment_id'] == config('ecommerce.payment_methods.bank')) {
            $rules['bank_name'] = 'required|max:100';
            $rules['branch_name'] = 'required|max:100';
            $rules['account_number'] = 'required|max:100';
            $rules['account_name'] = 'required|max:100';
            $rules['transaction_number'] = 'required|max:100';
            $rules['receipt'] = 'required|mimes:jpeg,png,jpg.pdf';
        }
        $rules['products'] = 'required|array';
        $rules['origin'] = 'required|max:50';
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
            'products.required' => translate('No products available for checkout', session()->get('api_locale')),
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
