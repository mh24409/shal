<?php

namespace App\Services;

use App\Models\ProductStock;
use App\Utility\ProductUtility;
use Combinations;
use App\Models\Color;
use App\Models\AttributeValue;
class ProductStockService
{
    public function store(array $data, $product)
    {
        $collection = collect($data);
        $options = ProductUtility::get_attribute_options($collection);
        $combinations = Combinations::makeCombinations($options);
        $variant = '';
        
        
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            $product->save();
            foreach ($combinations as $key => $combination) {
                $isDefault = 0 ;
                $str = ProductUtility::get_combination_string($combination, $collection);
                $key = str_replace([' ', '+', '%20'], '_', $str);
                 $fullKey = "img_" . $key;
                $imageArray = request($fullKey);
                if($imageArray ==null)
                {
                    $fullKey = "img_" . $key . "_";
                    $imageArray = request($fullKey);
                }             
                $imageString = implode(',', $imageArray);
                $price= request()['price_' . str_replace('.', '_', $str)];
                $wholesale_price_variant = request()['price_' . str_replace('.', '_', $str)];
                $wholesale_price = request()['price_' . str_replace('.', '_', $str)];
                if($price ==null)
                {
                    $price=request("price_$key");
                }
                if (request()['default_variation'] == $str) {
                    $isDefault = 1 ;
                } 
                
                $product_stock = new ProductStock();
               
                $product_stock->product_id = $product->id;
                $product_stock->variant = $str;
                $product_stock->default = $isDefault;
                $product_stock->price = $price;
                $product_stock->item_group_id = $product->item_group_id;
                $product_stock->wholesale_price_variant = request("Wholesale_price_variant_$key");
                $product_stock->wholesale_price = request("Wholesale_price_$key");
                $product_stock->cost_price = request("cost_price_$key");
                $product_stock->sku = request("sku_$key");
                $product_stock->suits = request("suits_$key");

                $product_stock->qty = request("qty_$key");
                $product_stock->image = $imageString;
                
                $product_stock->save();
                $variantParts = explode('-', $product_stock->variant);
                $color_id = null;
                $attribute_id = null;
                if (count($variantParts) === 2) {
                    $colorName = $variantParts[0];
                    $attributeValue = $variantParts[1];
                } elseif (count($variantParts) === 1) {
                    if ($product->has_color) {
                        $colorName = $variantParts[0];
                        $attributeValue = null;
                    } else {
                        $colorName = null;
                        $attributeValue = $variantParts[0];
                    }
                } else {
                    $colorName = null;
                    $attributeValue = null;
                } 
                if ($colorName != null) {
                    $color = Color::where('name', $colorName)->first();
                    if ($color) {
                        $color_id = $color->id;
                    }
                }
                if ($attributeValue != null) {
                    $attribute = AttributeValue::where('value', $attributeValue)->first();
                    if ($attribute) {
                        $attribute_id = $attribute->id;
                    }
                }  
                $product_stock->color_id = $color_id ;
                $product_stock->attribute_id = $attribute_id;
                $product_stock->save();
            }
        } else {
            unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
            $qty = $collection['current_stock'];
            $price = $collection['unit_price'];
            unset($collection['current_stock']);
            $data = $collection->merge(compact('variant', 'qty', 'price'))->toArray();
            ProductStock::create($data);
        }
        
    }

    public function product_duplicate_store($product_stocks, $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product_new->id;
            $product_stock->variant     = $stock->variant;
            $product_stock->price       = $stock->price;
            $product_stock->sku         = $stock->sku;
            $product_stock->qty         = $stock->qty;
            $product_stock->save();
        }
    }
}
