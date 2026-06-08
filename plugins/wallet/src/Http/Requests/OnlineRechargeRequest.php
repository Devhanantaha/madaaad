<?php

namespace Plugin\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;

class OnlineRechargeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules(Request $request)
    {
        $rules = [];
        $rules['payment_method'] = 'required|numeric';
        $rules['currency'] = 'required|numeric';


        $minimum_recharge_amount = getEcommerceSetting('minimum_wallet_recharge_amount');
        $converted_minimum_recharge_amount = currencyExchange($minimum_recharge_amount, false, $request->currency);

        $rules['recharge_amount'] = 'required|numeric|min:' . $converted_minimum_recharge_amount;
        $rules['origin'] = 'required|max:50';

        return $rules;
    }

    public function messages()
    {
        return [
            'recharge_amount.required' => translate('Amount is required'),
            'payment_method.required' => translate('Payment method is required'),
            'payment_method.numeric' => translate('Select valid payment method'),
            'currency.required' => translate('Currency is required'),
            'recharge_amount.min' => 'The minimum recharge amount must be at least ' . currencyExchange(getEcommerceSetting('minimum_wallet_recharge_amount'), true, request('currency'), false) . '.',

        ];
    }
}
