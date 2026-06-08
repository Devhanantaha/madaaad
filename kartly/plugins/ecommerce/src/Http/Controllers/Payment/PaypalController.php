<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Models\OrderRequest;
use PaypalServerSdkLib\Models\PurchaseUnitRequest;
use PaypalServerSdkLib\Models\AmountWithBreakdown;
use PaypalServerSdkLib\Models\OrderApplicationContext;
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use Plugin\Ecommerce\Http\Controllers\Payment\PaymentController;

class PaypalController extends Controller
{

    protected $total_payable_amount;
    protected $paypal_client_id;
    protected $paypal_client_secret;
    protected $is_active_sandbox;
    protected $currency = 'USD';

    public function __construct()
    {
        $this->currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paypal'), 'paypal_currency');
        $this->paypal_client_id = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paypal'), 'paypal_client_id');
        $this->paypal_client_secret = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paypal'), 'paypal_client_secret');
        $this->is_active_sandbox = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.paypal'), 'sandbox');
    }

    private function getClient()
    {
        $environment = $this->is_active_sandbox == 1
            ? Environment::SANDBOX
            : Environment::PRODUCTION;

        return PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    $this->paypal_client_id,
                    $this->paypal_client_secret
                )
            )
            ->environment($environment)
            ->build();
    }

    public function index($payment_queue)
    {
        $this->total_payable_amount = (new PaymentController())->convertCurrency($this->currency, $payment_queue->amount ?? 0);

        $amount = new AmountWithBreakdown(
            $this->currency,
            number_format($this->total_payable_amount, 2, '.', '')
        );

        $purchaseUnit = new PurchaseUnitRequest($amount);
        $purchaseUnit->setReferenceId((string) rand(000000, 999999));

        $orderRequest = new OrderRequest(
            CheckoutPaymentIntent::CAPTURE,
            [$purchaseUnit]
        );

        $applicationContext = new OrderApplicationContext();
        $applicationContext->setCancelUrl(route('paypal.cancel', ['pi' => $payment_queue?->uid]));
        $applicationContext->setReturnUrl(route('paypal.success', ['pi' => $payment_queue?->uid]));
        $orderRequest->setApplicationContext($applicationContext);

        try {
            $client = $this->getClient();
            $response = $client->getOrdersController()->createOrder([
                'body' => $orderRequest,
                'prefer' => 'return=representation',
            ]);

            $order = $response->getResult();
            $links = $order->getLinks();

            // Find the approve link to redirect the payer
            $approveUrl = null;
            if ($links) {
                foreach ($links as $link) {
                    if ($link->getRel() === 'approve') {
                        $approveUrl = $link->getHref();
                        break;
                    }
                }
            }

            if ($approveUrl) {
                return Redirect::to($approveUrl);
            }

            return (new PaymentController)->payment_failed($payment_queue?->uid);
        } catch (Exception $e) {
            return (new PaymentController)->payment_failed($payment_queue?->uid);
        }
    }


    public function cancel($pi, Request $request)
    {
        return (new PaymentController)->payment_cancel($pi);
    }

    public function success($pi, Request $request)
    {
        try {
            $client = $this->getClient();
            $response = $client->getOrdersController()->captureOrder([
                'id' => $request->token,
                'prefer' => 'return=representation',
            ]);

            $order = $response->getResult();
            $payment_id = 'id-' . $order->getId();
            return (new PaymentController)->payment_success($pi, json_encode($payment_id));
        } catch (Exception $e) {
            return (new PaymentController)->payment_failed($pi);
        }
    }
}
