<?php
namespace Webkul\Midtrans\Payment;

use Midtrans\Snap;
use Midtrans\Config;
use Webkul\Payment\Payment\Payment;

class Midtrans extends Payment
{
    /**
    * Payment method code
    *
    * @var string
    */
    protected $code = 'midtrans';

    /**
    * Get redirect URL for payment
    *
    * @return string
    */
    public function getRedirectUrl()
    {
        // Here we will configure Midtrans payment redirection logic
        return route('payment.midtrans.redirect');
    }

    /**
    * Get snap token for payment
    *
    * @param  \App\Models\Order $order
    * @return string
    */
    public function getSnapToken($order)
    {
        // Configuring Midtrans API keys
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $transactionDetails = [
            'order_id' => $order->id,
            'gross_amount' => $order->total_amount,
        ];

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $item->name,
            ];
        }

        $transaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
            ],
        ];

        try {
            // Create the Snap token
            return Snap::getSnapToken($transaction);
        } catch (\Exception $e) {
            return null;
        }
    }
}
