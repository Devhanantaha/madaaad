<?php

namespace Plugin\Wallet\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugin\Ecommerce\Models\Customers;
use Plugin\Ecommerce\Models\PaymentMethods;
use Plugin\Ecommerce\Models\PaymentQueue;
use Plugin\Wallet\Repositories\WalletRepository;
use Plugin\Wallet\Http\Requests\OnlineRechargeRequest;
use Plugin\Wallet\Http\Requests\OfflineRechargeRequest;
use Plugin\Wallet\Http\ApiResource\OfflinePaymentResource;
use Plugin\Wallet\Http\ApiResource\CustomerWalletTransactionResource;

class WalletApiController extends Controller
{
    protected $wallet_repository;

    public function __construct(WalletRepository $wallet_repository)
    {
        $this->wallet_repository = $wallet_repository;
    }

    /**
     * Will return payment methods
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentMethods()
    {
        $offline_methods = new OfflinePaymentResource($this->wallet_repository->activeOfflinePaymentMethods());
        $online_methods = $this->wallet_repository->activeOnlinePaymentMethods();
        return response()->json(
            [
                'success' => true,
                'online_methods' => $online_methods,
                'offline_methods' => $offline_methods,
            ]
        );
    }
    /**
     * Will store offline payment
     * 
     * @param OfflineRechargeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOfflinePayment(OfflineRechargeRequest $request)
    {
        $transaction_image = null;
        if ($request->has('transaction_image') && $request['transaction_image']) {
            $image = $request['transaction_image'];
            $transaction_image = saveFileInStorage($image, 'wallet');
        }

        $amount = convertToDefaultCurrency($request['recharge_amount'], $request['currency']);

        $data = [
            'entry_type' => config('ecommerce.wallet_entry_type.credit'),
            'recharge_type' => config('ecommerce.wallet_recharge_type.offline'),
            'customer_id' => auth()->user()->id,
            'transaction_id' => $request['transaction_id'],
            'payment_method_id' => $request['payment_method'],
            'recharge_amount' => $amount,
            'document' => $transaction_image,
            'added_by' => null,
            'status' => config('ecommerce.wallet_transaction_status.pending'),
        ];

        $res = $this->wallet_repository->storeWalletTraction($data);
        if ($res) {
            //Send notification to admin
            $customer = Customers::find(auth()->user()->id);
            $message = "Recharged wallet via offline";
            if ($customer != null) {
                $message = $customer->name . " recharged wallet via offline";
            }

            \Plugin\Wallet\Repositories\WalletNotification::sendCustomerWalletRechargeNotification($message);

            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function onlineWalletRecharge(OnlineRechargeRequest $request)
    {
        try {
            $amount = convertToDefaultCurrency($request['recharge_amount'], $request['currency']);
            $base_url = url('/');
            $payment_method = PaymentMethods::find($request['payment_method']);
            if ($payment_method != null) {

                $payment_queue = new PaymentQueue();
                $payment_queue->customer_id = auth('jwt-customer')->user()->id;
                $payment_queue->amount = $amount;
                $payment_queue->currency_id = $request['currency'];
                $payment_queue->payment_method_id = $payment_method->id;
                $payment_queue->type = "wallet_recharge";
                $payment_queue->return_url = "/dashboard/wallet";
                $payment_queue->origin = $request['origin'] ?? "web";
                $payment_queue->save();

                $url = $base_url . '/payment/' . Str::slug($payment_method->name) . '/pay/' . $payment_queue->uid;
                return response()->json([
                    'success' => true,
                    'url' => $url,
                    'config' => $this->paymentMethodConfig($payment_queue)
                ]);
            } else {
                return response()->json([
                    'success' => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function paymentMethodConfig($payment_queue)
    {
        try {

            $config = (new \Plugin\Ecommerce\Http\Controllers\Payment\PaymentController)->getPaymentMethodConfig($payment_queue->uid);
            return $config;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return null;
        }
    }

    /**
     * Will return customer wallet transaction
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerWalletTransaction(Request $request)
    {
        return new CustomerWalletTransactionResource($this->wallet_repository->customerWalletTransactions(auth()->user()->id, $request));
    }
    /**
     * Will return customer wallet summary
     */
    public function customerWalletSummary(Request $request)
    {
        $res = $this->wallet_repository->customerWalletSummary(auth()->user()->id);
        if ($res) {
            return response()->json(
                [
                    'success' => true,
                    'summary' => $res
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
}
