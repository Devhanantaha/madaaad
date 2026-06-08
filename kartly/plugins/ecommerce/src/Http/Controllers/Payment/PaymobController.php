<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Plugin\Ecommerce\Http\Controllers\Payment\PaymentController;
use Plugin\Ecommerce\Models\Customers;
use Plugin\Ecommerce\Models\GuestCustomers;

class PaymobController extends Controller
{

    protected $apiKey;
    protected $integrationId;
    protected $hmacSecret;
    protected $total_payable_amount;
    protected $iframe_key;
    protected $currency = "EGP";

    public function __construct()
    {

        $this->currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paymob'), 'paymob_currency');
        $this->apiKey = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paymob'), 'paymob_api_key');
        $this->integrationId = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paymob'), 'paymob_integration_id');
        $this->hmacSecret = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paymob'), 'paymob_hmac_secret');
        $this->iframe_key = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paymob'), 'paymob_iframe_key');
    }

    public function index($payment_queue)
    {
        $this->total_payable_amount = (new PaymentController())->convertCurrency($this->currency, $payment_queue->amount ?? 0);
        $amountCents = $this->total_payable_amount * 100; // Paymob requires amount in cents
        $amountCents = (int)$amountCents;
        $authToken = $this->authenticate();
        $orderId = $this->createOrder($authToken, $amountCents);
        $paymentKey = $this->getPaymentKey($authToken, $orderId, $amountCents, $payment_queue);

        return view('plugin/ecommerce::payments.gateways.paymob.index', [
            'payment_key' => $paymentKey,
            'iframe_key' => $this->iframe_key,
            'pi' => $payment_queue->uid
        ]);
    }

    public function callback(Request $request)
    {
        $transactionData = $request->all();
        $pi = $transactionData['pi'] ?? null;
        try {
            if (!$pi) {
                return (new PaymentController)->payment_failed('');
            }

            if (!$this->validateHMAC($transactionData)) {
                return (new PaymentController)->payment_failed($pi);
            }

            if ($transactionData['success'] == 'true') {
                return (new PaymentController)->payment_success($pi, $transactionData['id']);
            } else {
                return (new PaymentController)->payment_failed($pi);
            }
        } catch (\Exception $e) {
            return (new PaymentController)->payment_failed($pi);
        }
    }


    //Authentication to obtain token
    public function authenticate()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);

        return $response->json()['token'];
    }

    //Order Registration
    public function createOrder($token, $amountCents)
    {
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $amountCents,
            'currency' => $this->currency,
            'items' => [],

        ]);

        return $response->json()['id'];
    }

    //Payment Key Request
    public function getPaymentKey($token, $orderId, $amountCents, $payment_queue)
    {
        try {
            $customer = null;
            if ($payment_queue->customer_id != null) {
                $customer = Customers::where('id', $payment_queue->customer_id)->first();
            }

            if ($payment_queue->guest_customer_id != null) {
                $customer = GuestCustomers::where('id', $payment_queue->guest_customer_id)->first();
            }

            $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
                'pid' => $payment_queue->uid,
                'auth_token' => $token,
                'amount_cents' => $amountCents,
                'expiration' => 3600,
                'order_id' => $orderId,
                'billing_data' => [
                    "apartment" => "NA",
                    "email" => $customer?->email,
                    "floor" => "NA",
                    "first_name" => $customer?->name,
                    "last_name" => $customer?->name,
                    "street" => "NA",
                    "building" => "NA",
                    "phone_number" => isset($customer->phone) ? $customer->phone : 'xxxxxxxxxx',
                    "postal_code" => "NA",
                    "city" => "NA",
                    "country" => "NA",
                    "state" => "NA"
                ],
                'currency' => $this->currency,
                'integration_id' => $this->integrationId,
            ]);

            return $response->json()['token'];
        } catch (\Exception $e) {
            return (new PaymentController)->payment_failed($payment_queue->uid);
        }
    }

    public function verifyPayment($transactionId)
    {
        $authToken = $this->authenticate();

        $response = Http::get("https://accept.paymob.com/api/acceptance/transactions/{$transactionId}", [
            'auth_token' => $authToken,
        ]);

        return $response->json();
    }

    protected function validateHMAC($data)
    {
        $hmac = $data['hmac'] ?? '';

        // Paymob requires these specific fields concatenated in this exact order
        $fields = [
            'amount_cents', 'created_at', 'currency', 'error_occured',
            'has_parent_transaction', 'id', 'integration_id', 'is_3d_secure',
            'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment',
            'is_voided', 'order', 'owner', 'pending',
            'source_data.pan', 'source_data.sub_type', 'source_data.type', 'success',
        ];

        $concatenated = '';
        foreach ($fields as $field) {
            $concatenated .= $data[$field] ?? '';
        }

        $computedHMAC = hash_hmac('sha512', $concatenated, $this->hmacSecret);

        return hash_equals($computedHMAC, $hmac);
    }
}
