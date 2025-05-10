<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->isSanitized = config('midtrans.is_sanitized');
        $this->is3ds = config('midtrans.is_3ds');
    }

    public function getSnapToken($params)
    {
        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = $this->isSanitized;
        \Midtrans\Config::$is3ds = $this->is3ds;

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return $snapToken;
    }

    public function createTransaction($params)
    {
        $transaction = [
            'transaction_details' => [
                'order_id' => $params['order_id'],
                'gross_amount' => $params['total_amount'],
            ],
            'customer_details' => [
                'first_name' => $params['name'],
                'email' => $params['email'],
                'phone' => $params['phone'],
                'billing_address' => [
                    'address' => $params['address'],
                ],
            ],
            'item_details' => $params['items'],
            'enabled_payments' => [
                'credit_card', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'gopay', 'shopeepay'
            ],
        ];


        return $this->getSnapToken($transaction);
    }

    public function verifyPayment($orderId)
    {
        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;

        try {
            $status = \Midtrans\Transaction::status($orderId);
            return (object)$status;
        } catch (\Exception $e) {
            return false;
        }
    }
}