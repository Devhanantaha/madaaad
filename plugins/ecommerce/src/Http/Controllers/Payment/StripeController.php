<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugin\Ecommerce\Models\PaymentQueue;
use Plugin\Ecommerce\Http\Controllers\Payment\PaymentController;

class StripeController extends Controller
{
    protected $total_payable_amount;
    protected $stripe_public_key;
    protected $stripe_secret_key;
    protected $currency = "USD";

    public function __construct()
    {
        $this->currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.stripe'), 'stripe_currency');
        $this->stripe_public_key = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.stripe'), 'stripe_public_key');
        $this->stripe_secret_key = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.stripe'), 'stripe_secret_key');
    }
    /**
     * Initial stripe payment
     */
    public function index($payment_queue)
    {
        return view('plugin/ecommerce::payments.gateways.stripe.index')->with(
            [
                'stripe_public_key' => $this->stripe_public_key,
                'pi' => $payment_queue->uid
            ]
        );
    }
    /**
     * Create stripe payment
     */
    public function create_checkout_session(Request $request)
    {

        $pi = $request['pi'];
        $payment_queue = PaymentQueue::where('uid', $request['pi'])->where('status', 0)->first();
        $amount = (new PaymentController())->convertCurrency($this->currency, $payment_queue->amount);
        $amount = round($amount * 100);

        \Stripe\Stripe::setApiKey($this->stripe_secret_key);
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $this->currency,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success.payment', ['pi' => $pi]),
            'cancel_url' => route('stripe.cancel.payment', ['pi' => $pi]),
        ]);
        return response()->json(['id' => $session->id, 'status' => 200]);
    }
    /**
     * Success stripe payment
     */
    public function success($pi)
    {
        try {
            return (new PaymentController)->payment_success($pi);
        } catch (\Exception $e) {
            return (new PaymentController)->payment_failed($pi);
        }
    }
    /**
     * Cancel Stripe payment
     */
    public function cancel($pi)
    {
        return (new PaymentController)->payment_failed($pi);
    }
}
