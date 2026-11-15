<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    /**
     * Set konfigurasi Midtrans dari config/midtrans.php
     */
    public static function configure(): void
    {
        $serverKey = config('midtrans.server_key');

        if (empty($serverKey)) {
            throw new \Exception('MIDTRANS_SERVER_KEY belum terbaca dari config.');
        }

        Config::$serverKey    = trim($serverKey);
        Config::$isProduction = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Buat transaksi dan kembalikan Snap Token
     */
    public static function createTransaction(string $orderId, $amount, array $customerDetails): string
    {
        self::configure();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => $customerDetails,
        ];

        return Snap::getSnapToken($params);
    }
}