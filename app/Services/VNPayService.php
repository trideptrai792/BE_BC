<?php

namespace App\Services;

class VNPayService
{
    public function createPaymentUrl(array $opt): string
    {
        $vnpUrl = config('services.vnpay.url');
        $tmn = config('services.vnpay.tmn_code');
        $secret = config('services.vnpay.hash_secret');
        // VNPay expects GMT+7 timestamps; force Asia/Ho_Chi_Minh to avoid expiry errors
        $now = now('Asia/Ho_Chi_Minh');

        $params = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $tmn,
            'vnp_Amount' => (int)$opt['amount_vnd'] * 100,
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => (string)$opt['txn_ref'],
            'vnp_OrderInfo' => (string)$opt['order_info'],
            'vnp_OrderType' => 'other',
            'vnp_Locale' => 'vn',
            'vnp_ReturnUrl' => config('services.vnpay.return_url'),
            'vnp_IpAddr' => (string)($opt['ip'] ?? '127.0.0.1'),
            'vnp_CreateDate' => $now->format('YmdHis'),
            'vnp_ExpireDate' => $now->copy()->addMinutes(15)->format('YmdHis'),
        ];

        ksort($params);

        $hashData = [];
        foreach ($params as $k => $v) {
            $hashData[] = $k . '=' . urlencode((string) $v);
        }
        $hashDataStr = implode('&', $hashData);

        $secureHash = hash_hmac('sha512', $hashDataStr, $secret);
        $params['vnp_SecureHash'] = $secureHash;

        return $vnpUrl . '?' . http_build_query($params);
    }

    public function verify(array $input): bool
    {
        $secret = config('services.vnpay.hash_secret');

        $secureHash = $input['vnp_SecureHash'] ?? '';
        unset($input['vnp_SecureHash'], $input['vnp_SecureHashType']);

        ksort($input);

        $hashData = [];
        foreach ($input as $k => $v) {
            if ($v === null || $v === '') continue;
            $hashData[] = $k . '=' . urlencode((string) $v);
        }
        $hashDataStr = implode('&', $hashData);

        $check = hash_hmac('sha512', $hashDataStr, $secret);
        return hash_equals($check, $secureHash);
    }
}
