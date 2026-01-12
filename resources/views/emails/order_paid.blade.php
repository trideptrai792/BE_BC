<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thanh toan thanh cong</title>
</head>
<body>
    <p>Chao {{ $order->name }},</p>
    <p>Don hang #{{ $order->id }} da thanh toan thanh cong.</p>
    <p>Tong tien: {{ number_format($amountVnd, 0, ',', '.') }} VND</p>
    <p>Cam on ban da mua hang!</p>
</body>
</html>
