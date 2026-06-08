<?php

namespace Plugin\Ecommerce\Http\Controllers\Payment;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Plugin\Ecommerce\Models\Orders;
use App\Http\Controllers\Controller;
use Core\Exceptions\CurrencyException;
use Plugin\Ecommerce\Models\Customers;
use Plugin\Ecommerce\Models\PaymentQueue;
use Plugin\Ecommerce\Models\PaymentMethods;
use Plugin\Ecommerce\Models\OrderHasProducts;
use Plugin\Ecommerce\Models\PaymentTransaction;
use Plugin\Ecommerce\Repositories\EcommerceNotification;
use Plugin\Ecommerce\Repositories\PaymentMethodRepository;
use Plugin\Ecommerce\Http\Requests\PaymentMethodCredentialRequest;

class PaymentController extends Controller
{
    /**
     * Convert currency
     */
    public function convertCurrency($convert_to_currency, $amount)
    {
        $system_currency = \Plugin\Ecommerce\Models\Currency::where('id', \Plugin\Ecommerce\Repositories\SettingsRepository::getEcommerceSetting('default_currency'))
            ->select('code', 'conversion_rate')
            ->first();
        $to_currency = \Plugin\Ecommerce\Models\Currency::where('code', $convert_to_currency)
            ->select('code', 'conversion_rate', 'number_of_decimal')
            ->first();

        if ($to_currency != null) {
            $decimal_point = $to_currency->number_of_decimal != null ? $to_currency->number_of_decimal : 2;
            $converted_amount = ($amount / $system_currency->conversion_rate) * $to_currency->conversion_rate;
            return round($converted_amount, $decimal_point);
        }
        throw new CurrencyException("Currency error. $convert_to_currency currency is not configured.");
    }
    /**
     * Order payment process
     */
    public function createPayment(Request $request, $payment_method, $pi)
    {

        if ($request->has('success') && $request['success'] == 'failed') {
            return view('plugin/ecommerce::payments.errors.payment_failed')->with(['gateway' => $payment_method]);
        }


        if ($payment_method && $pi) {

            //check payment queue
            $payment_queue = PaymentQueue::where('uid', $request['pi'])->where('status', 0)->first();

            if ($payment_queue == null) {
                abort(404);
            }

            if ($payment_method == 'stripe') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\StripeController)->index($payment_queue);
            }

            if ($payment_method == 'paypal') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\PaypalController)->index($payment_queue);
            }

            if ($payment_method == 'paddle') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\PaddleController)->index($payment_queue);
            }

            if ($payment_method == 'sslcommerz') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\SSLCommerzController)->index($payment_queue);
            }

            if ($payment_method == 'paystack') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\PaystackController)->index($payment_queue);
            }

            if ($payment_method == 'razorpay') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\RazorpayController)->index($payment_queue);
            }

            if ($payment_method == 'mollie') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\MollieController)->index($payment_queue);
            }

            if ($payment_method == 'gpay') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\GpayController)->index($payment_queue);
            }

            if ($payment_method == 'paymob') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\PaymobController)->index($payment_queue);
            }

            if ($payment_method == 'mercado-pago') {
                return (new \Plugin\Ecommerce\Http\Controllers\Payment\MercadoPagoController)->index($payment_queue);
            }

            return redirect('/404');
        } else {
            return redirect('/404');
        }
    }

    public function getPaymentMethodConfig($pq)
    {

        $payment_queue = PaymentQueue::where('uid', $pq)->where('status', 0)->first();
        if ($payment_queue->payment_method_id == config('ecommerce.payment_methods.razorpay')) {
            return (new \Plugin\Ecommerce\Http\Controllers\Payment\RazorpayController)->config($payment_queue);
        }

        return [];
    }
    /**
     * Payment success
     */
    public function payment_success($pi, $payment_info = null)
    {

        try {
            DB::beginTransaction();
            $payment_queue = PaymentQueue::where('uid', $pi)->where('status', 0)->first();

            if ($payment_queue == null) {
                return redirect('/404');
            }

            //Payment method
            $payment_method = PaymentMethods::find($payment_queue->payment_method_id);

            //Wallet payment
            if ($payment_queue->type == 'wallet_recharge') {
                if (isActivePlugin('wallet')) {
                    $waller_transaction = new \Plugin\Wallet\Models\WalletTransaction;
                    $waller_transaction->entry_type = config('ecommerce.wallet_entry_type.credit');
                    $waller_transaction->recharge_type = config('ecommerce.wallet_recharge_type.online');
                    $waller_transaction->customer_id = $payment_queue->customer_id;
                    $waller_transaction->added_by = null;
                    $waller_transaction->document = null;
                    $waller_transaction->recharge_amount = $payment_queue->amount;
                    $waller_transaction->status = config('ecommerce.wallet_transaction_status.accept');
                    $waller_transaction->payment_method_id = $payment_queue->payment_method_id;
                    $waller_transaction->transaction_id = null;
                    $waller_transaction->save();

                    //Send Notification
                    $customer = Customers::find($payment_queue->customer_id);
                    $message = "Wallet recharge successfully";
                    if ($customer != null) {
                        $message = $customer->name . " recharged wallet";
                    }

                    \Plugin\Wallet\Repositories\WalletNotification::sendCustomerWalletRechargeNotification($message);
                }
            }


            //Checkout payment
            if ($payment_queue->type == 'checkout') {
                $order_id = $payment_queue->order_id;
                $order_info = Orders::where('id', $order_id)->first();

                //Update order information
                if ($order_info != null) {
                    $order_info->payment_status = config('ecommerce.order_payment_status.paid');
                    $order_info->save();
                    $orders_items = OrderHasProducts::where('order_id', $order_id)->get();
                    foreach ($orders_items as $item) {
                        $item->total_paid = $item->totalPayableAmount();
                        $item->payment_status = config('ecommerce.order_payment_status.paid');
                        $item->save();
                    }
                }
                //Send Notification
                $message = "Order payment competed by " . $payment_method?->name;
                EcommerceNotification::sendCustomerOrderPaymentCompletedNotification($order_id, $message);
            }

            //Store payment info
            $payment = new PaymentTransaction;
            $payment->payment_method = $payment_method?->name;
            $payment->paid_amount = $payment_queue->amount;
            $payment->payment_for = $payment_queue->type;
            $payment->payment_info = $payment_info;
            $payment->guest_customer = $payment_queue->guest_customer_id;
            $payment->customer_id = $payment_queue->customer_id;
            $payment->save();

            //update payment queue
            $payment_queue->status = 1;
            $payment_queue->save();

            DB::commit();
            return $this->getRedirectURL($pi, 'SUCCESS');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->getRedirectURL($pi, 'FAILED');
        }
    }
    /**
     * Payment unsuccessful
     */
    public function payment_failed($pi)
    {
        return $this->getRedirectURL($pi, 'FAILED');
    }

    /**
     * Payment cancel
     */
    public function payment_cancel($pi)
    {
        return $this->getRedirectURL($pi, 'CANCELLED');
    }


    public function getRedirectURL($pi, $status)
    {
        $payment_queue = PaymentQueue::where('uid', $pi)->first();


        if ($payment_queue->origin == "app") {

            if ($payment_queue->type == 'wallet_recharge') {
                return response()->json([
                    'success' => true,
                    'type' => 'wallet_recharge',
                    'payment_status' => $status
                ], 200);
            }

            if ($payment_queue->type == 'checkout') {
                return response()->json([
                    'success' => true,
                    'payment_status' => $status,
                    'type' => 'checkout',
                    'order_id' => $payment_queue?->order_id
                ], 200);
            };
        }

        if ($payment_queue->origin != 'app') {
            if ($payment_queue->type == 'wallet_recharge') {
                //wallet recharge
                return redirect()->to('/dashboard/wallet?recharge=' . $status);
            }

            if ($payment_queue->type == 'checkout') {
                //checkout
                return redirect()->to('/order-success' . '/' . $payment_queue->order_id . '?payment_status=' . $status);
            }
        }
    }

    /**
     * Will return payment transaction history
     * 
     * @return mixed
     */
    public function transactionHistory(Request $request)
    {
        $query = PaymentTransaction::query();

        if ($request->has('payment_method') && $request['payment_method'] != null) {
            $query = $query->where('payment_method', $request['payment_method']);
        }

        //Filter by date
        if ($request->has('transaction_date') && $request['transaction_date'] != null) {
            $date_range = explode(' to ', $request['transaction_date']);
            if (sizeof($date_range) > 1) {
                if ($date_range[0] == $date_range[1]) {
                    $query = $query->whereDate('created_at', $date_range[0]);
                } else {
                    $query = $query->whereBetween('created_at', $date_range);
                }
            }
        }
        //Filter by search key
        if ($request->has('search') && $request['search'] != null) {
            $query = $query->whereHas('customer_info', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request['search'] . '%')
                    ->orWhere('email', 'like', '%' . $request['search'] . '%');
            });
        }

        $transactions = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();

        $payment_methods = PaymentMethods::whereNotIn('id', [config('ecommerce.payment_methods.cod')])->get()->map(function ($item) {
            return [
                'name' => $item->name,
                'logo' => getFilePath($item->logo, false),
                'id' => $item->id
            ];
        });
        return view('plugin/ecommerce::payments.transactions.index')->with(
            [
                'transactions' => $transactions,
                'payment_methods' => $payment_methods
            ]
        );
    }
    /**
     * Will return payment methods
     * 
     * @return mixed
     */
    public function paymentMethods()
    {
        $payment_methods = (new PaymentMethodRepository)->paymentMethods();
        return view('plugin/ecommerce::payments.gateways.gateway_list')->with(
            [
                'payment_methods' => $payment_methods
            ]
        );
    }
    /**
     * Will update payment method status
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function changePaymentMethodStatus(Request $request)
    {
        $res = (new PaymentMethodRepository)->paymentMethodUpdateStatus($request['id']);
        if ($res) {
            toastNotification('success', translate('Status updated successfully'));
        } else {
            toastNotification('error', translate('Action failed'));
        }
    }
    /**
     * Will update payment method credential
     * 
     * @param PaymentMethodCredentialRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentMethodCredential(PaymentMethodCredentialRequest $request)
    {
        $res = (new PaymentMethodRepository)->updatePaymentMethodCredential($request);

        if ($res) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
}
