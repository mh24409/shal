<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FlashDealProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => $data->product_id,
                    'name' => $data->product->name,
                    'image' => uploaded_asset($data->product->thumbnail_img),
                    'price' => home_discounted_base_price($data->product),
                    'links' => [
                        'details' => route('products.show', $data->product_id),
                    ]
                ];
            })
        ];
    }
}
