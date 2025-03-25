<?php

namespace App\Models;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'category_id',
            'brand_id',
            'unit_price',
            'Wholesale_price',
            'Wholesale_price_variant',
            'cost_price',
            'purchase_price',
            'unit',
            'current_stock',
            'meta_title',
            'meta_description',
            'quantity'
        ];
    }
    public function map($product): array
    {
        $qty = 0;
        foreach ($product->stocks as $key => $stock) {
            $qty += $stock->qty;
        }
        return [
            $product->name, $product->description,
            $product->category_id, $product->brand_id,
            $product->unit_price, $product->Wholesale_price, $product->Wholesale_price_variant, $product->cost_price,
            $product->purchase_price,
            $product->unit,
            $product->current_stock,
            $product->meta_title,
            $product->meta_description,
            $qty,
        ];
    }
}
