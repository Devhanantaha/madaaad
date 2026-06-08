<?php

namespace Plugin\Ecommerce\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodCredentialRequest extends FormRequest
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
        if ($request->has('payment_id')) {
            switch ($request['payment_id']) {
                case config('ecommerce.payment_methods.stripe'):
                    return [
                        'stripe_currency' => 'required',
                        'stripe_public_key' => 'required',
                        'stripe_secret_key' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.paypal'):
                    return [
                        'paypal_currency' => 'required',
                        'paypal_client_id' => 'required',
                        'paypal_client_secret' => 'required',
                    ];
                    break;

                case config('ecommerce.payment_methods.paddle'):
                    return [
                        'paddle_currency' => 'required',
                        'paddle_vendor_id' => 'required',
                        'paddle_public_key' => 'required',
                        'paddle_vendor_auth_code' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.sslcommerz'):
                    return [
                        'ssl_currency' => 'required',
                        'sslcz_store_id' => 'required',
                        'sslcz_store_password' => 'required',
                        'ssl_currency' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.paystack'):
                    return [
                        'paystack_currency' => 'required',
                        'paystack_public_key' => 'required',
                        'paystack_secret_key' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.razorpay'):
                    return [
                        'razorpay_currency' => 'required',
                        'razorpay_key_id' => 'required',
                        'razorpay_key_secret' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.mollie'):
                    return [
                        'mollie_currency' => 'required',
                        'mollie_api_key' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.bank'):
                    return [
                        'bank_instruction' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.gpay'):
                    return [
                        'gpay_currency' => 'required',
                        'gpay_marchant_name' => 'required',
                        'gpay_marchant_id' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.paymob'):
                    return [
                        'paymob_currency' => 'required',
                        'paymob_api_key' => 'required',
                        'paymob_integration_id' => 'required',
                        'paymob_iframe_key' => 'required',
                        'paymob_hmac_secret' => 'required',
                    ];
                    break;
                case config('ecommerce.payment_methods.mercado-pago'):
                    return [
                        'mc_pago_currency' => 'required',
                        'mc_pago_access_token' => 'required',
                        'mc_pago_public_key' => 'required',
                    ];
                    break;
                default:
                    return [];
            }
        } else {
            return [];
        }
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'stripe_public_key.required' => translate('Public key is required'),
            'stripe_private_key.required' => translate('Private key is required'),
            'paypal_currency.required' => translate('Please select a currency'),
            'paypal_client_id.required' => translate('Client ID is required'),
            'paypal_client_secret.required' => translate('Client secret is required'),
            'paddle_currency.required' => translate('Please select a currency'),
            'paddle_vendor_id.required' => translate('Vendor ID is required'),
            'paddle_public_key.required' => translate('Public key is required'),
            'paddle_vendor_auth_code.required' => translate('Vendor auth code is required'),
            'ssl_currency.required' => translate('Please select a currency'),
            'sslcz_store_id.required' => translate('Store ID is required'),
            'sslcz_store_password.required' => translate('Store password is required'),
            'paystack_currency.required' => translate('Please select a currency'),
            'paystack_public_key.required' => translate('Public key is required'),
            'paystack_secret_key.required' => translate('Secret key is required'),
            'razorpay_currency.required' => translate('Please select a currency'),
            'razorpay_key_id.required' => translate('Key ID is required'),
            'razorpay_key_secret.required' => translate('Key secret is required'),
            'mollie_currency.required' => translate('Please select a currency'),
            'mollie_api_key.required' => translate('API key is required'),
            'bank_instruction.required' => translate('Bank instruction is required'),
            'gpay_currency.required' => translate('Please select a currency'),
            'gpay_marchant_name.required' => translate('Marchant name is required'),
            'gpay_marchant_id.required' => translate('Marchant ID is required'),
            'paymob_currency.required' => translate('Please select a currency'),
            'paymob_api_key.required' => translate('API key is required'),
            'paymob_integration_id.required' => translate('Integration ID is required'),
            'paymob_iframe_key.required' => translate('Iframe key is required'),
            'paymob_hmac_secret.required' => translate('HMAC secret is required'),
            'mc_pago_currency.required' => translate('Please select a currency'),
            'mc_pago_access_token.required' => translate('Access token is required'),
            'mc_pago_public_key.required' => translate('Public key is required'),
        ];
    }
}
