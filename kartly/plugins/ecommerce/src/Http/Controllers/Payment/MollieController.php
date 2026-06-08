<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Plugin\Ecommerce\Http\Controllers\Payment\PaymentController;

class MollieController extends Controller
{
    protected $total_payable_amount;
    protected $mollie_api_key;
    protected $currency = 'USD';

    public function __construct()
    {
        $this->currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.mollie'), 'mollie_currency');
        $this->mollie_api_key = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.mollie'), 'mollie_api_key');
    }

    /**
     * Mollie create payment
     */
    public function index($payment_queue)
    {
        try {
            $this->total_payable_amount = (new PaymentController())->convertCurrency($this->currency, $payment_queue->amount ?? 0);
            $apiKey = $this->mollie_api_key;
            $description = 'Payment with mollie';

            $payload = [
                'amount' => [
                    'currency' => $this->currency,
                    'value' => number_format($this->total_payable_amount, 2),
                ],
                'description' => $description,
                'redirectUrl' => route('payment.callback', ['pi' => $payment_queue->uid]),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.mollie.com/v2/payments');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ]);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);
            $redirect_url = $response['_links']['checkout']['href'];
            session()->put('payment_id', $response['id']);

            return Redirect::away($redirect_url);
        } catch (Exception $ex) {
            return (new PaymentController)->payment_failed($payment_queue->uid);
        }
    }

    /**
     * Mollie webhook — called by Mollie when payment status changes
     */
    public function paymentWebhook(\Illuminate\Http\Request $request)
    {
        try {
            $paymentId = $request->input('id');
            if (!$paymentId) {
                return response('', 200);
            }

            $apiKey = $this->mollie_api_key;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.mollie.com/v2/payments/' . $paymentId);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
            ]);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            // Webhooks are fire-and-forget; always return 200
            return response('', 200);
        } catch (\Exception $e) {
            return response('', 200);
        }
    }

    /**
     * Mollie callback function
     */
    public function paymentCallback($pi)
    {
        try {
            $paymentId = session()->get('payment_id');
            $apiKey = $this->mollie_api_key;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.mollie.com/v2/payments/' . $paymentId);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
            ]);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            if ($response['status'] == 'paid') {
                return (new PaymentController)->payment_success($pi);
            } elseif ($response['status'] == 'canceled') {
                return (new PaymentController)->payment_cancel($pi);
            } else {
                return (new PaymentController)->payment_failed($pi);
            }
        } catch (Exception $ex) {
            return (new PaymentController)->payment_failed($pi);
        }
    }
}
