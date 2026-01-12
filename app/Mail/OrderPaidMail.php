<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public int $amountVnd
    ) {
    }

    public function build()
    {
        return $this->subject('Thanh toan thanh cong #' . $this->order->id)
            ->view('emails.order_paid');
    }
}
