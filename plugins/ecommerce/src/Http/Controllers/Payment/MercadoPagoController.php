<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Plugin\Ecommerce\Http\Controllers\Payment\PaymentController;

class MercadoPagoController extends Controller
{

    protected $accessToken;
    protected $public_key;
    protected $total_payable_amount;
    protected $currency = "BRL";

    public function __construct()
    {
        $this->accessToken = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.mercado-pago'), 'mc_pago_access_token');
        $this->public_key = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.mercado-pago'), 'mc_pago_public_key');
    }

    public function index($payment_queue)
    {
        $this->currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.mercado-pago'), 'mc_pago_currency');
        $this->total_payable_amount = (new PaymentController())->convertCurrency($this->currency, $payment_queue->amount ?? 0);

        $payload = [
            "items" => [
                [
                    "title" => 'Product Title',
                    "quantity" => 1,
                    "currency_id" => $this->currency,
                    "unit_price" => $this->total_payable_amount
                ]
            ],
            "back_urls" => [
                "success" => route('mercadopago.payment.success.ecommerce', ['pi' => $payment_queue->uid]),
                "failure" => route('mercadopago.payment.failure.ecommerce', ['pi' => $payment_queue->uid]),
                "pending" => route('mercadopago.payment.pending.ecommerce', ['pi' => $payment_queue->uid]),
            ],
            "auto_return" => "approved",
        ];

        // Make the API call
        $response = Http::withToken($this->accessToken)
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);


        if ($response->successful()) {
            $responseBody = $response->json();
            return redirect($responseBody['init_point']);
        }

        return (new PaymentController)->payment_failed($payment_queue->uid);
    }

    public function success($pi, Request $request)
    {
        $data = $this->getPaymentDetails($request['payment_id']);
        if (!empty($data) && isset($data['id']) && !empty($data['id'])) {
            return (new PaymentController)->payment_success($pi, $data['id']);
        }

        return (new PaymentController)->payment_failed($pi);
    }

    public function failure($pi, Request $request)
    {
        return (new PaymentController)->payment_failed($pi);
    }

    public function pending($pi, Request $request)
    {
        return (new PaymentController)->payment_failed($pi);
    }

    public function getPaymentDetails($paymentId)
    {
        $url = "https://api.mercadopago.com/v1/payments/{$paymentId}?access_token=" . $this->accessToken;
        $response = Http::get($url);
        return $response->json();
    }
}
