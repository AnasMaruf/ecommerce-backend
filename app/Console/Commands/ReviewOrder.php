<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReviewOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:review-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to review the order, if you have not paid and it has expired then the status is added as expired.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = \App\Models\Order\Order::where('payment_expired_at', '<=', now())->get();
        foreach($orders as $order){
            $order->update([
                'payment_expired_at' => null
            ]);
            $order->status()->create([
                'status' => 'failed',
                'description' => 'Payment has expired'
            ]);
        }
    }
}
