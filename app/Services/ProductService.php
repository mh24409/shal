<?php

namespace App\Services;

use App\Models\Color;
use App\Models\Product;
use App\Models\User;
use App\Utility\ProductUtility;
use Combinations;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductService
{
    public function store(array $data)
    {

        $collection = collect($data);

        $approved = 1;
        if (auth()->user()->user_type == 'seller') {
            $user_id = auth()->user()->id;
            if (get_setting('product_approve_by_admin') == 1) {
                $approved = 0;
            }
        } else {
            $user_id = User::where('user_type', 'admin')->first()->id;
        }
        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);
        $discount_start_date = null;
        $discount_end_date   = null;
        if ($collection['date_range'] != null) {
            $date_var               = explode(" to ", $collection['date_range']);
            $discount_start_date = strtotime($date_var[0]);
            $discount_end_date   = strtotime($date_var[1]);
        }
        unset($collection['date_range']);
        if(isset($collection['details_keys']) && isset($collection['details_values'])){
            $details_array = array_combine($collection['details_keys'],$collection['details_values']);
            $product_details = json_encode($details_array);
            $collection['product_details'] = count($details_array) > 0 ? $product_details : null;;
        }else{
            $collection['product_details'] = null;
        }
        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }

        if ($collection['meta_img'] == null) {
            $collection['meta_img'] = $collection['thumbnail_img'];
        }


        $shipping_cost = 0;
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $colors = json_encode(array());
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors = json_encode($collection['colors']);
        }
        $options = ProductUtility::get_attribute_options($collection);

        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                unset($collection['price_' . str_replace('.', '_', $str)]);
                unset($collection['wholesale_price_' . str_replace('.', '_', $str)]);
                unset($collection['sku_' . str_replace('.', '_', $str)]);
                unset($collection['qty_' . str_replace('.', '_', $str)]);
                unset($collection['img_' . str_replace('.', '_', $str)]);
            }
        }
        unset($collection['colors_active']);

        $choice_options = array();
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();
            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
                $attribute_data = array();
                // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                foreach ($collection[$str] as $key => $eachValue) {
                    // array_push($data, $eachValue->value);
                    array_push($attribute_data, $eachValue);
                }
                unset($collection[$str]);

                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }

        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $attributes = json_encode($collection['choice_no']);
            unset($collection['choice_no']);
        } else {
            $attributes = json_encode(array());
        }

        $published = 1;
        if ($collection['button'] == 'unpublish' || $collection['button'] == 'draft') {
            $published = 0;
        }
        unset($collection['button']);
        if(isset($collection['design'])){
            $a = $collection['design'] ;
            $collection['design'] = json_encode($a) ;
        }
        if(isset($collection['event'])){
             $b = $collection['event'] ;
            $collection['event'] = json_encode($b) ;
        }
        if(isset($collection['fabric_type'])){
            $c = $collection['fabric_type'] ;
            $collection['fabric_type'] = json_encode($c) ;
        }
        if(isset($collection['close_type'])){
           $d = $collection['close_type'];
            $collection['close_type'] = json_encode($d) ;
        }
       if(isset($collection['hand_type'])){
           $e = $collection['hand_type'] ;
            $collection['hand_type'] = json_encode($e) ;
        }
        
//                 $data = $collection->merge([
//     'user_id' => $user_id,
//     'approved' => $approved,
//     'discount_start_date' => $discount_start_date,
//     'discount_end_date' => $discount_end_date,
//     'shipping_cost' => $shipping_cost,
//     'slug' => $slug,
//     'colors' => $colors,
//     'choice_options' => $choice_options,
//     'attributes' => $attributes,
//     'published' => $published,
// ])->toArray();
        
//         return Product::create($data); 
        
        $data = $collection->merge(compact(
            'user_id',
            'approved',
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            'colors',
            'choice_options',
            'attributes',
            'published', 
        ))->toArray();
        
        return Product::create($data); 
       
    }

    public function update(array $data, Product $product)
    {
        $collection = collect($data);

        $slug = Str::slug($collection['name']);
        $slug = $collection['slug'] ? Str::slug($collection['slug']) : Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        if (addon_is_activated('refund_request') && !isset($collection['refundable'])) {
            $collection['refundable'] = 0;
        }

        if (!isset($collection['is_quantity_multiplied'])) {
            $collection['is_quantity_multiplied'] = 0;
        }

        if (!isset($collection['cash_on_delivery'])) {
            $collection['cash_on_delivery'] = 0;
        }
        if (!isset($collection['featured'])) {
            $collection['featured'] = 0;
        }
        if (!isset($collection['todays_deal'])) {
            $collection['todays_deal'] = 0;
        }
        if (!isset($collection['trending'])) {
            $collection['trending'] = 0;
        }
        if (!isset($collection['best_selling'])) {
            $collection['best_selling'] = 0;
        }
        if (!isset($collection['lastUpdated_featured'])) {
            $collection['lastUpdated_featured'] = Carbon::now();
        }

        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);
        $discount_start_date = null;
        $discount_end_date   = null;
        if ($collection['date_range'] != null) {
            $date_var               = explode(" to ", $collection['date_range']);
            $discount_start_date = strtotime($date_var[0]);
            $discount_end_date   = strtotime($date_var[1]);
        }
        unset($collection['date_range']);
        if(isset($collection['details_keys']) && isset($collection['details_values'])){
            $details_array = array_combine($collection['details_keys'],$collection['details_values']);
            $product_details = json_encode($details_array);
            $collection['product_details'] = count($details_array) > 0 ? $product_details : null;;
        }else{
            $collection['product_details'] = null;
        }
        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }

        if ($collection['meta_img'] == null) {
            $collection['meta_img'] = $collection['thumbnail_img'];
        }

        if ($collection['lang'] != env("DEFAULT_LANGUAGE")) {
            unset($collection['name']);
            unset($collection['unit']);
            unset($collection['description']);
        }
        unset($collection['lang']);


        $shipping_cost = 0;
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);

        $colors = json_encode(array());
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors = json_encode($collection['colors']);
        }

        $options = ProductUtility::get_attribute_options($collection);

        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);

                unset($collection['price_' . str_replace('.', '_', $str)]);
                unset($collection['sku_' . str_replace('.', '_', $str)]);
                unset($collection['qty_' . str_replace('.', '_', $str)]);
                unset($collection['img_' . str_replace('.', '_', $str)]);
            }
        }

        unset($collection['colors_active']);

        $choice_options = array();
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();
            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
                $attribute_data = array();
                // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                foreach ($collection[$str] as $key => $eachValue) {
                    // array_push($data, $eachValue->value);
                    array_push($attribute_data, $eachValue);
                }
                unset($collection[$str]);

                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }

        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $attributes = json_encode($collection['choice_no']);
            unset($collection['choice_no']);
        } else {
            $attributes = json_encode(array());
        }

        unset($collection['button']);
        if(isset($collection['design'])){
            $a = $collection['design'] ;
            $collection['design'] = json_encode($a) ;
        }
        if(isset($collection['event'])){
             $b = $collection['event'] ;
            $collection['event'] = json_encode($b) ;
        }
        if(isset($collection['fabric_type'])){
            $c = $collection['fabric_type'] ;
            $collection['fabric_type'] = json_encode($c) ;
        }
        if(isset($collection['close_type'])){
           $d = $collection['close_type'];
            $collection['close_type'] = json_encode($d) ;
        }
       if(isset($collection['hand_type'])){
           $e = $collection['hand_type'] ;
            $collection['hand_type'] = json_encode($e) ;
        }
        
        
//         $data = $collection->merge([
//     'discount_start_date' => $discount_start_date,
//     'discount_end_date' => $discount_end_date,
//     'shipping_cost' => $shipping_cost,
//     'slug' => $slug,
//     'colors' => $colors,
//     'choice_options' => $choice_options,
//     'attributes' => $attributes,
// ])->toArray();

        
        $data = $collection->merge(compact(
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            'colors',
            'choice_options',
            'attributes', 
        ))->toArray();

        $product->update($data);
        if(isset($collection['questions_answer'])){
            $product->questions()->sync($collection['questions_answer']);
        }
         return $product ; 
    }
}
