<?php

return [
    'cashondelivery'  => [
        'code'        => 'cashondelivery',
        'title'       => 'Cash On Delivery',
        'description' => 'Cash On Delivery',
        'class'       => 'Webkul\Payment\Payment\CashOnDelivery',
        'active'      => true,
        'sort'        => 1,
    ],

    'moneytransfer'   => [
        'code'        => 'moneytransfer',
        'title'       => 'Money Transfer',
        'description' => 'Money Transfer via Midtrans', // Ubah deskripsi jika perlu
        'class'       => 'Webkul\Payment\Payment\MoneyTransfer', // Jika sudah diubah sesuai implementasi Midtrans
        'active'      => true,
        'sort'        => 2,
    ],
];
