<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class StockCron extends Command
{

    protected $signature = 'stock:cron';


    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $product = Product::whereNotNull('id_from_pos')->select('id_from_pos', 'id')->get();

        foreach ($product as $product) {
            $stock = ProductStock::where('product_id', $product->id)->first();
            $qty = $stock->qty;
            $response = Http::withoutVerifying()->post('https://pos.elamriaa.com/api/products/update_stock_from_active', [
                'product_id' => $product->id_from_pos,
                'qty' => $qty
            ]);
        }
    }
}
