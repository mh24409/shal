<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ClassifiedProductDetailCollection;
use App\Http\Resources\V2\ClassifiedProductMiniCollection;
use Cache;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Product;
use App\Models\FlashDeal;
use Illuminate\Http\Request;
use App\Utility\SearchUtility;
use App\Utility\CategoryUtility;
use App\Http\Resources\V2\ProductCollection;
use App\Http\Resources\V2\FlashDealCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\DigitalProductDetailCollection;
use App\Models\CustomerProduct;
use Carbon\Carbon;
use App\Models\Upload;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductMiniCollection(Product::latest()->paginate(10));
    }

    public function show($id)
    {
        return new ProductDetailCollection(Product::where('id', $id)->get());
        // if (Product::findOrFail($id)->digital==0) {
        //     return new ProductDetailCollection(Product::where('id', $id)->get());
        // }elseif (Product::findOrFail($id)->digital==1) {
        //     return new DigitalProductDetailCollection(Product::where('id', $id)->get());
        // }
    }


    // public function admin()
    // {
    //     return new ProductCollection(Product::where('added_by', 'admin')->latest()->paginate(10));
    // }
 
    public function seller($id, Request $request)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::where('added_by', 'seller')->where('user_id', $shop->user_id);
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection($products->latest()->paginate(10));
    }

    public function category($id, Request $request)
    {
        $category_ids = CategoryUtility::children_ids($id);
        $category_ids[] = $id;

        $products = Product::whereIn('category_id', $category_ids)->physical();

        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }


    public function brand($id, Request $request)
    {
        $products = Product::where('brand_id', $id)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }

        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return Cache::remember('app.todays_deal', 86400, function () {
            $products = Product::where('todays_deal', 1)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->latest()->get());
        });
    }

    public function flashDeal()
    {
        return Cache::remember('app.flash_deals', 86400, function () {
            $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
            return new FlashDealCollection($flash_deals);
        });
    }

    public function featured()
    {
        $products = Product::where('featured', 1)->physical();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function digital()
    {
        $products = Product::digital();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    

    public function bestSeller()
    {
        return Cache::remember('app.best_selling_products', 86400, function () {
            $products = Product::orderBy('num_of_sale', 'desc')->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }
      public function addlast2()
        {
           $products = Product::latest()->physical()->limit(20)->get();
           $response = [];
           foreach ($products as $data) {
            $tags = explode(',', $data->tags);
            $response[] = [
                'id' => $data->id,
                'name' => $data->getTranslation('name'),
                'description'=>  strip_tags($data->getTranslation('description')),
                'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                'discount' => "-" . discount_in_percentage($data) . "%",
                'stroked_price' => home_base_price($data),
                'main_price' => home_discounted_base_price($data),
                'rating' => (double) $data->rating,
                'sales' => (integer) $data->num_of_sale,
                'tags' => $tags,
                'links' => [
                    'details' => route('products.show', $data->id),
                ],
            ];
        }
           return response()->json([
              'data' => $response,
              'success' => true,
              'status' => 200,
          ]);
        }
    public function related($id)
    {
        return Cache::remember("app.related_products-$id", 86400, function () use ($id) {
            $product = Product::find($id);
            $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }

    public function topFromSeller($id)
    {
        return Cache::remember("app.top_from_this_seller_products-$id", 86400, function () use ($id) {
            $product = Product::find($id);
            $products = Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->physical();

            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }


    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1)->physical();

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
            SearchUtility::store($name);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }

        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductMiniCollection(filter_products($products)->paginate(10));
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }


        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $stockQuantity = $product_stock->qty;


        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'price' => (float)convert_price($price),
            'price_string' => format_price(convert_price($price)),
            'stock' => intval($stockQuantity),
            'image' => $product_stock->image == null ? "" : uploaded_asset($product_stock->image)
        ]);
    }
    public function check_cache(Request $request)
    { 
        $CategoryDate = Carbon::parse($request->input('featureCategory'));
        $categoriesCount = Category::where('lastUpdated_featured', '>', $CategoryDate)->count();
        $categories = $categoriesCount > 0;
        $best_product_date = Carbon::parse($request->input('bestSeller'));
        $bestSeller = Product::orderBy('num_of_sale', 'desc')->where('updated_at', '>', $best_product_date)->count();
        $bestSeller = $bestSeller > 0;
        
        $featured_product = Carbon::parse($request->input('getFeatured'));
        $featured_product = Product::where('lastUpdated_featured', '>', $featured_product)->count();
        
        $featured_product = $featured_product > 0;
        
        $sliders=json_decode(get_setting('home_slider_images'), true);
        $sliderDate=Carbon::parse($request->input('slider'));
        $slider=Upload::whereIn('id',$sliders)->where('updated_at','>', $sliderDate)->count();
        $slider = $slider > 0;
        
        $banner1=json_decode(get_setting('home_banner1_images'), true);
        $bannerDate=Carbon::parse($request->input('banner1'));
        $banner1=Upload::whereIn('id',$banner1)->where('updated_at','>', $bannerDate)->count();
        $banner1 = $banner1 > 0;
        
        
        $rProductsDate = Carbon::parse($request->input('rProducts'));
        $rProducts = Product::latest()->physical()->where('updated_at', '>', $rProductsDate)->limit(20)->count();
        $rProducts = $rProducts > 0;
        
        $today_dealDate = Carbon::parse($request->input('aLLProducts'));
        $today_deal = Product::where('todays_deal', 1)->where('updated_at', '>', $today_dealDate)->count();
        $today_deal = $today_deal > 0;
        
        $banner2=json_decode(get_setting('home_banner2_images'), true);
        $banner2Date=Carbon::parse($request->input('banner2'));
        $banner2=Upload::whereIn('id',$banner2)->where('updated_at','>', $banner2Date)->count();
        $banner2 = $banner2 > 0;
        
        $status =[
         'featureCategoryStatus' => $categories,
         'bestSellerStatus' => $bestSeller,
         'getFeaturedStatus' => $featured_product,
         'sliderStatus' => $slider,
         'banner1Status' => $banner1,
         'rProductsStatus' => $rProducts,
         'tProductsStatus' => $today_deal,
         'banner2Status' => $banner2,
         'aLLProductsStatus' => true,
         ];
         $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

         $dates = [];
            $dates['featureCategoryDateTime'] = $currentDateTime;
            $dates['bestSellerDateTime'] = $currentDateTime;
            $dates['getFeaturedDateTime'] = $currentDateTime;
            $dates['sliderDateTime'] = $currentDateTime;
            $dates['banner1DateTime'] = $currentDateTime;
            $dates['rProductsDateTime'] = $currentDateTime;
            $dates['aLLProductsDateTime'] = $currentDateTime;
            $dates['tProductsDateTime'] = $currentDateTime;
            $dates['banner2DateTime'] = $currentDateTime;
            
        
                
            
        
            return response()->json([
                'status' => $status,
                'dates' => $dates,
            ]);
    }
}
