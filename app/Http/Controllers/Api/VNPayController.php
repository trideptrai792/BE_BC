<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\VNPayService;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Mail;

class VNPayController extends Controller
{
    public function returnUrl(Request $request, VNPayService $vnp)
    {
        $data = $request->query();

        if (!$vnp->verify($data)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $orderId = (int)($data['vnp_TxnRef'] ?? 0);
        $rsp = $data['vnp_ResponseCode'] ?? '';
        $stt = $data['vnp_TransactionStatus'] ?? '';

        $order = Order::find($orderId);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $ok = ($rsp === '00' && $stt === '00');

        if ($ok && $order->payment_status !== 'paid') {
            $order->payment_status = 'paid';
            $order->payment_ref = $data['vnp_TransactionNo'] ?? null;
            $order->paid_at = now();
            $order->save();

            if (!empty($order->email)) {
                Mail::to($order->email)->send(new OrderPaidMail($order, (int) $data['vnp_Amount'] / 100));
            }
        }

        return redirect('http://localhost:3000/payment-result?' . http_build_query($data));
    }

    public function ipn(Request $request, VNPayService $vnp)
    {
        $data = $request->query();

        if (!$vnp->verify($data)) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $orderId = (int)($data['vnp_TxnRef'] ?? 0);
        $amount = (int)($data['vnp_Amount'] ?? 0);
        $rsp = $data['vnp_ResponseCode'] ?? '';
        $stt = $data['vnp_TransactionStatus'] ?? '';
        $txnNo = $data['vnp_TransactionNo'] ?? null;

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        $ok = ($rsp === '00' && $stt === '00');

        if ($ok) {
            if ($order->payment_status !== 'paid') {
                $order->status = 2;
                $order->payment_status = 'paid';
                $order->payment_ref = $txnNo;
                $order->paid_at = now();
                $order->save();

                if (!empty($order->email)) {
                    Mail::to($order->email)->send(new OrderPaidMail($order, (int) $amount / 100));
                }
            }
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }
}
