<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class GpayController extends Controller
{
    protected $total_payable_amount;
    protected $marchant_id;
    protected $marchant_name;
    protected $currency = 'USD';
    protected $mode = 'TEST';

    public function __construct()
    {
        $this->currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.gpay'), 'gpay_currency');
        $this->marchant_id = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.gpay'), 'gpay_marchant_id');
        $this->marchant_name = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.gpay'), 'gpay_marchant_name');
        $sandbox = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.gpay'), 'sandbox');

        $this->mode = $sandbox == '1' ? 'TEST' : 'PRODUCTION';
    }

    /**
     * Initiate payment with gpay
     */
    public function index($payment_queue)
    {
        $this->total_payable_amount = (new PaymentController())->convertCurrency($this->currency, $payment_queue->amount ?? 0);
        $data = [
            'currency' => $this->currency,
            'total_payable_amount' => $this->total_payable_amount,
            'marchant_id' => $this->marchant_id,
            'marchant_name' => $this->marchant_name,
            'mode' => $this->mode,
            'pi' => $payment_queue->uid
        ];

        return view('plugin/ecommerce::payments.gateways.gpay.index', $data);
    }

    /**
     * Will handle gpay payment status
     */
    public function googlepayPaymentSubmit(Request $request)
    {
        $pi = $request['pi'];
        try {
            if ($request['payment_status'] == 1) {
                return (new PaymentController)->payment_success($pi, "Marchant-ID " . $request['marchant_id']);
            } else {
                return (new PaymentController)->payment_failed($pi);
            }
        } catch (Exception $ex) {
            return (new PaymentController)->payment_failed($pi);
        }
    }
}
