<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    private const STATUS_LABELS = [
        0 => 'Cancelled',
        1 => 'Pending',
        2 => 'Confirmed',
        3 => 'Shipping',
        4 => 'Completed',
        5 => 'Finished',
    ];

    private function statusOptions(): array
    {
        $opts = [];
        foreach (self::STATUS_LABELS as $k => $v) {
            $opts[] = ['value' => $k, 'label' => $v];
        }
        return $opts;
    }

    private function statusText(int $status): string
    {
        return self::STATUS_LABELS[$status] ?? 'Unknown';
    }

    public function index(Request $request)
    {
        $perPage = (int)($request->query('per_page', 20));
        $q = trim((string)$request->query('q', ''));
        $status = $request->query('status');
        $from = $request->query('from');
        $to = $request->query('to');

        $query = Order::query()
            ->withCount('details')
            ->orderByDesc('id');

        if ($status !== null && $status !== '') {
            $query->where('status', (int)$status);
        }

        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                if (ctype_digit($q)) {
                    $w->orWhere('id', (int)$q);
                }
                $w->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $paginated = $query->paginate($perPage);

        $dataArr = $paginated->toArray();
        $rows = $dataArr['data'] ?? [];

        foreach ($rows as &$row) {
            $row['status_text'] = $this->statusText((int)($row['status'] ?? -1));
        }
        unset($row);

        $dataArr['data'] = $rows;
        $dataArr['status_options'] = $this->statusOptions();

        return response()->json($dataArr);
    }

    public function show(Order $order)
    {
        $order->load(['details.product']);

        $subtotal = (int)$order->details->sum('amount');
        $discount = (int)$order->details->sum('discount');
        $total = max(0, $subtotal - $discount);

        $orderArr = $order->toArray();
        $orderArr['status_text'] = $this->statusText((int)($orderArr['status'] ?? -1));

        return response()->json([
            'order' => $orderArr,
            'summary' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
            ],
            'status_options' => $this->statusOptions(),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'integer', 'in:0,1,2,3,4,5'],
        ]);

        $order->status = (int)$data['status'];
        $order->updated_by = $request->user()->id;
        $order->save();

        return response()->json([
            'ok' => true,
            'order_id' => $order->id,
            'status' => $order->status,
            'status_text' => $this->statusText((int)$order->status),
        ]);
    }
}
