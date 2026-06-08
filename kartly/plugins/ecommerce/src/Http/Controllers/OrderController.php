<?php

namespace Plugin\Ecommerce\Http\Controllers;

use BPDF;
use NPDF;
use Milon\Barcode\DNS1D;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Plugin\Ecommerce\Repositories\OrderRepository;
use Plugin\Ecommerce\Http\Requests\DeliveryStatusUpdateRequest;

class OrderController extends Controller
{

    protected $order_repository;

    public function __construct(OrderRepository $order_repository)
    {
        $this->order_repository = $order_repository;
        $this->middleware('t' . 'h' . 'e' . 'me' . 'lo' . 'o' . 'k' . 's');
    }

    /**
     * Will return inhouse orders
     * 
     * @return mixed
     */
    public function inhouseOrders(Request $request)
    {
        $orders = $this->order_repository->orderList($request, config('ecommerce.order_type.home_delivery'), 'inhouse', null);
        $order_counter = $this->order_repository->orderCounter(config('ecommerce.order_type.home_delivery'));
        return view('plugin/ecommerce::orders.inhouse_orders.index')->with(
            [
                'orders' => $orders,
                'order_counter' => $order_counter
            ]
        );
    }

    /**
     * Will redirect order details page
     * 
     * @param Int $id
     * @return mixed
     */
    public function orderDetails($id)
    {
        $order_details = $this->order_repository->orderDetails($id);
        return view('plugin/ecommerce::orders.details')->with(
            [
                'order_details' => $order_details
            ]
        );
    }
    /**
     * Will return Order status details
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function orderStatusDetails(Request $request)
    {
        $order_details = $this->order_repository->orderDetails($request['id']);
        return view('plugin/ecommerce::orders.status_details')->with(
            [
                'order_details' => $order_details
            ]
        );
    }
    /**
     * Will update delivery status
     * 
     * @param DeliveryStatusUpdateRequest $request
     * @return mixed
     */
    public function updateOrderStatus(DeliveryStatusUpdateRequest $request)
    {
        $res = $this->order_repository->updateOrderStatus($request);
        if ($res) {
            return response()->json(
                [
                    'success' => true,
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
    /**
     * Will cancel an order
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function cancelOrder(Request $request)
    {
        $res = $this->order_repository->cancelOrder($request['order_id']);

        if ($res) {
            toastNotification('success', translate('Order cancelled successfully'));
        } else {
            toastNotification('error', translate('Order cancel failed'));
        }
        return redirect()->back();
    }

    public function deleteOrder(Request $request)
    {
        $res = $this->order_repository->deleteOrder($request['order_id']);

        if ($res) {
            toastNotification('success', translate('Order deleted successfully'));
        } else {
            toastNotification('error', translate('Order delete failed'));
        }
        return redirect()->back();
    }

    /**
     * Will cancel an item
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed 
     */
    public function cancelOrderItem(Request $request)
    {
        $res = $this->order_repository->changeOrderItemStatus($request['item_id'], $request['order_id'], config('ecommerce.order_delivery_status.cancelled'));
        if ($res) {
            toastNotification('success', translate('Item has been cancelled'));
        } else {
            toastNotification('error', translate('Action failed'));
        }

        return redirect()->back();
    }

    /**
     * Will accept order 
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function acceptOrder(Request $request)
    {
        $res = $this->order_repository->acceptOrder($request['order_id']);
        if ($res) {
            toastNotification('success', translate('Order accept successfully'));
        } else {
            toastNotification('error', translate('Order accept failed'));
        }
        return redirect()->back();
    }
    /**
     * Will bulk action of orders
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function orderBulkAction(Request $request)
    {
        $res = $this->order_repository->orderBulkAction($request);

        if ($res) {
            toastNotification('success', translate('Bulk action completed successfully'));
        } else {
            toastNotification('error', translate('Bulk action failed'));
        }
    }
    /**
     * Will print shipping label
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function printShippingLabel(Request $request)
    {
        try {
            if (!$request->has('shipping_label_products')) {
                toastNotification('error', 'No product selected');
                return redirect()->back();
            }

            $shipping_label_content = $this->order_repository->getShippingLabelContent($request['order_id'], $request['shipping_label_products']);
            $qr_data = collect($shipping_label_content)->toJson();

            $sellerName = "Seller Name";
            $vatNumber = "323456789012343";
            $invoiceTotal = "1000.00";
            $vatTotal = "0";
            $timestamp = Carbon::now()->toIso8601String();

            $tlvData = '';
            $tlvData .= $this->encodeTLV(1, $sellerName);
            $tlvData .= $this->encodeTLV(2, $vatNumber);
            $tlvData .= $this->encodeTLV(3, $timestamp);
            $tlvData .= $this->encodeTLV(4, $invoiceTotal);
            $tlvData .= $this->encodeTLV(5, $vatTotal);

            $base64Encoded = base64_encode($tlvData);

            $qr_code = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($base64Encoded));

            $barcode_generator = new DNS1D();
            $order_code_bar_code = $barcode_generator->getBarcodePNG($shipping_label_content['order_code'], 'C39+');
            $tracking_id_bar_code = $barcode_generator->getBarcodePNG($shipping_label_content['tracking_id'], 'C39+');

            $font_family = "Roboto";
            $local = getLocale();

            if ($local  == 'bd') {
                $font_family = 'Bangla';
            }

            if ($local  == 'sa') {
                $font_family = 'Arabic';
            }

            if ($local  == 'il') {
                $font_family = 'Hebrew';
            }

            $default_currency = Cache::get('default-currency-details');
            $currency_font = 'Arial Unicode MS';
            if ($default_currency->symbol == '₹') {
                $currency_font = 'Roboto';
            }

            $data = [
                'title' => $shipping_label_content['order_code'],
                'date' => date('m/d/Y'),
                'order_info' => $shipping_label_content,
                'qr_code' => $qr_code,
                'order_code_bar_code' => $order_code_bar_code,
                'tracking_id_bar_code' => $tracking_id_bar_code,
                'font_family' => $font_family,
                'currency_font' => $currency_font
            ];

            $default_language = getLocale();
            $is_rtl = DB::table('tl_languages')
                ->where('code', '=', $default_language)
                ->where('is_rtl', '=', 1)
                ->exists();

            if ($is_rtl) {
                $qrCodePath = public_path('uploaded/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');
                file_put_contents($qrCodePath, base64_decode($qr_code));
                $data['qr_code'] = url('public/uploaded/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');

                $pdf = NPDF::loadView('plugin/tlecommercecore::orders.invoice.shipping_label_rtl', $data, [], [
                    'default_font' => 'dejavusans',
                    'mode' => 'utf-8',
                    'margin_top' => 10,
                    'margin_right' => 10,
                    'margin_bottom' => 10,
                    'margin_left' => 10,
                    'padding_left' => 5,
                    'padding_right' => 5,
                ]);

                if ($request['action'] == 'preview') {
                    return $pdf->stream($shipping_label_content['order_code'] . '.pdf');
                } else {
                    return $pdf->download($shipping_label_content['order_code'] . '.pdf');
                }
            }


            $shipping_view = $is_rtl ? 'plugin/ecommerce::orders.invoice.shipping_label_rtl' : 'plugin/ecommerce::orders.invoice.shipping_label';
            $pdf = BPDF::loadView($shipping_view, $data)->set_option('isFontSubsettingEnabled', true);


            if ($request['action'] == 'preview') {
                return $pdf->stream();
            } else {
                return $pdf->download($shipping_label_content['order_code'] . '.pdf');
            }
        } catch (\Exception $e) {
            toastNotification('error', translate('Something went wrong. Please try again'));
            return redirect()->back();
        }
    }

    public function encodeTLV($tag, $value)
    {
        $length = strlen($value);
        return chr($tag) . chr($length) . $value;
    }
    /**
     * Will print order invoice
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     **/
    public function printInvoice(Request $request)
    {
        try {
            if (!$request->has('invoice_products')) {
                toastNotification('error', 'No product selected');
                return redirect()->back();
            }
            $invoice_data = $this->order_repository->getInvoiceData($request['order_id'], $request['invoice_products']);
            $qr_code = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($invoice_data['order_code']));
            $font_family = "Roboto";
            $local = getLocale();

            if ($local  == 'bd') {
                $font_family = 'Bangla';
            }

            if ($local  == 'sa') {
                $font_family = 'Arabic';
            }

            if ($local  == 'il') {
                $font_family = 'Hebrew';
            }

            $default_currency = Cache::get('default-currency-details');
            $currency_font = 'Arial Unicode MS';
            if ($default_currency->symbol == '₹') {
                $currency_font = 'Roboto';
            }

            $data = [
                'title' => $invoice_data['order_code'],
                'date' => date('m/d/Y'),
                'order_info' => $invoice_data,
                'qr_code' => $qr_code,
                'font_family' => $font_family,
                'currency_font' => $currency_font
            ];

            $default_language = getLocale();
            $is_rtl = DB::table('tl_languages')
                ->where('code', '=', $default_language)
                ->where('is_rtl', '=', 1)
                ->exists();

            $invoice_view = $is_rtl ?  'plugin/ecommerce::orders.invoice.invoice_rtl' : 'plugin/ecommerce::orders.invoice.invoice';
            $pdf = BPDF::loadView($invoice_view, $data)->set_option('isFontSubsettingEnabled', true);


            if ($request['action'] == 'preview') {
                return $pdf->stream();
            } else {
                return $pdf->download($invoice_data['order_code'] . '.pdf');
            }
        } catch (\Exception $e) {
            toastNotification('error', translate('Something went wrong. Please try again'));
            return redirect()->back();
        }
    }
}
