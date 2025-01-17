<?php

namespace Webkul\Midtrans\Http\Controllers;

use Webkul\Midtrans\Payment\Midtrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(Midtrans $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function createPayment(Request $request)
    {
        $order = $request->order;  // Assume order is passed in the request
        $snapToken = $this->midtrans->getSnapToken($order);

        if ($snapToken) {
            return view('midtrans::payment', ['snapToken' => $snapToken]);
        } else {
            return response()->json(['error' => 'Payment creation failed'], 500);
        }
    }

    public function callback(Request $request)
    {
        // Handle the Midtrans callback here (e.g., update order status)
    }
}
