<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\cartMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;

class SendAbandonedCartEmails extends Command
{
    protected $signature = 'email:send-abandoned-carts';
    protected $description = 'Send abandoned cart emails';

    public function handle()
    {
        $abandonedCarts = Cart::with('user')
            ->where('user_id', '!=', null)
            ->get()
            ->groupBy('user_id');

        foreach ($abandonedCarts as $userId => $carts) {
            Mail::to($carts[0]['user']->email)->queue(new cartMail($userId));
        }

        $this->info('Abandoned cart emails sent successfully.');
    }
}
