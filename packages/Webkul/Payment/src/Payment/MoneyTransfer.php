<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Snap;
use Webkul\Checkout\Facades\Cart;

class MoneyTransfer extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'moneytransfer';

    /**
     * Midtrans Client Key.
     */
    protected $clientKey = 'SB-Mid-client-lEGSbctyPxMWTjrO'; // Ganti dengan Client Key yang sesuai

    /**
     * Midtrans Server Key.
     */
    protected $serverKey = 'SB-Mid-server-aSVKk_B-Mj72FVL7Ixw5aBD0'; // Ganti dengan Server Key yang sesuai

    /**
     * Konfigurasi Midtrans untuk menghubungkan dengan API.
     */
    public function __construct()
    {
        // Set up Midtrans configuration
        Config::$clientKey = $this->clientKey;
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = false; // Atur ke true jika sudah live (di produksi)
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Get redirect URL for Midtrans payment.
     *
     * @return string
     * @throws \Exception
     */
    public function getRedirectUrl()
    {
        // Ambil cart yang aktif
        $cart = Cart::getCart();
    
        if (!$cart) {
            throw new \Exception('Cart is empty.');
        }
    
        // Validasi jumlah transaksi
        if ($cart->grand_total <= 0) {
            throw new \Exception('Invalid cart total.');
        }
    
        // Membuat detail transaksi untuk Midtrans
        $transactionDetails = [
            'order_id' => 'ORDER-' . $cart->id, // Order ID unik
            'gross_amount' => (int) $cart->grand_total, // Total transaksi harus berupa integer
        ];
    
        // Detail barang dalam transaksi
        $items = [];
        foreach ($cart->items as $item) {
            $items[] = [
                'id' => $item->product->id,
                'price' => (int) $item->price, // Pastikan harga berupa integer
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }
    
        // Tambahkan pajak (tax) sebagai item
        if ($cart->tax_total > 0) {
            $items[] = [
                'id' => 'TAX',
                'price' => (int) $cart->tax_total,
                'quantity' => 1,
                'name' => 'Tax',
            ];
        }
    
        // Tambahkan biaya pengiriman (delivery charge) sebagai item
        if ($cart->selected_shipping_rate && $cart->selected_shipping_rate->price > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $cart->selected_shipping_rate->price,
                'quantity' => 1,
                'name' => 'Shipping Charge',
            ];
        }
    
        // Data pelanggan
        $customerDetails = [
            'first_name' => $cart->customer->first_name,
            'last_name' => $cart->customer->last_name,
            'email' => $cart->customer->email,
            'phone' => $cart->customer->phone,
        ];
    
        // Payload lengkap untuk Midtrans
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $items,
            'customer_details' => $customerDetails,
        ];
    
        try {
            // Membuat transaksi untuk Snap Midtrans
            $snapTransaction = Snap::createTransaction($params);
    
            // Mengembalikan URL untuk redirect ke halaman Midtrans
            return $snapTransaction->redirect_url;
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            \Log::error('Midtrans Error: ' . $e->getMessage());
            throw new \Exception('Failed to generate Midtrans redirect URL.');
        }
    }
    
    /**
     * Returns payment method additional information.
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        return [
            'title' => 'Pay via Midtrans',
            'value' => $this->clientKey,
        ];
    }

    /**
     * Returns payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/midtrans-logo.png', 'shop');
    }
}
