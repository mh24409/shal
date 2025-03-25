<?php

namespace App\Http\Controllers;

use Str;
use Cache;
use Artisan;
use Combinations;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Upload;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTax;
use App\Models\ProductStock;
use CoreComponentRepository;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Services\ProductService;
use App\Models\ProductTranslation;
use App\Services\ProductTaxService;
use App\Http\Requests\ProductRequest;
use App\Services\ProductStockService;
use App\Services\ProductFlashDealService;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomQuestion;
use App\Models\CustomQuestionTranslation;
class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;

        // Staff Permission Check
        $this->middleware(['permission:add_new_product'])->only('create');
        $this->middleware(['permission:show_all_products'])->only('all_products');
        $this->middleware(['permission:show_in_house_products'])->only('admin_products');
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
        $this->middleware(['permission:product_duplicate'])->only('duplicate');
        $this->middleware(['permission:product_delete'])->only('destroy');
    }
    public function admin_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }
    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function all_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $sort_date = null;
        $category_id = null;
        $products = Product::orderBy('created_at', 'desc')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->date != null) {
            $sort_date = explode('to', $request->date);
            if (isset($sort_date[0]) && isset($sort_date[1])) {
                $products = $products
                    ->whereBetween('created_at', [Carbon::parse($sort_date[0])->format('Y-m-d H:i:s'), Carbon::parse($sort_date[1])->format('Y-m-d H:i:s')]);
            }
            $sort_date = $request->date;
        }

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }


        if ($request->has('category_id') && $request->category_id != null) {
            $products = $products->where('category_id', $request->category_id);
            $category_id = $request->category_id;
        }
        $products = $products->paginate(15);
        $type = 'All';
        return view('backend.product.products.index', compact('products', 'sort_date', 'type', 'col_name', 'query', 'seller_id', 'sort_search', 'category_id'));
    }
    public function create()
    {
        CoreComponentRepository::initializeCache();
        //       $events=json_decode(get_setting('Events'));
        // foreach ($events as $event){
        //     return $event;

        // }

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.create', compact('categories'));
    }
    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }
    public function store(ProductRequest $request)
    {

        // return $request;
        $selectedSubCategories = $request->input('sub_category_id');
        $commaSeparatedString = implode(',', $selectedSubCategories);
        $request->merge(['sub_category_id' => $commaSeparatedString]);
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));

        $request->merge(['product_id' => $product->id]);
        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'default_variation', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        $lang = env('DEFAULT_LANGUAGE') ?? 'en';

        // Product Translations
        $request->merge([
            'lang' => $lang
        ]);
        if ($request->custom_question && $request->custom_answer) {  
            foreach ($request->custom_question as $key => $custom_quest) {
                CustomQuestion::create([
                    'product_id' => $product->id,
                    'question' => $custom_quest,
                    'answer' => $request->custom_answer[$key],
                ]);
            }
        }
        ProductTranslation::create($request->only([
            'lang', 'name', 'video_name', 'unit', 'description', 'long_description', 'product_id'
        ])); 
        flash(translate('Product has been inserted successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->back();
    }
    public function show($id)
    {
        //
    }
    public function admin_product_edit(Request $request, $id)
    {
        CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        $defaultSelectedCategoryIds = explode(',', $product->sub_category_id);
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang', 'defaultSelectedCategoryIds'));
    }
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }
    public function update(Request $request, Product $product)
    { 
        $selectedSubCategories = $request->input('sub_category_id');
        $commaSeparatedString = implode(',', $selectedSubCategories);
        $request->merge(['sub_category_id' => $commaSeparatedString]);
        if ($request->has('counter_down')) {
            $product->counter_down = 1;
            $product->save();
        } else {
            $product->counter_down = 0;
            $product->save();
        }

        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);
        //Product Stock
        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }
        $request->merge(['product_id' => $product->id]);
        $this->productStockService->store($request->only([
            'colors_active', 'default_variation', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);
        
        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }
        
        if ($request->custom_question && $request->custom_answer) { 
            CustomQuestion::where('product_id', $product->id)->delete();
            foreach ($request->custom_question as $key => $custom_quest) {
                CustomQuestion::create([
                    'product_id' => $product->id,
                    'question' => $custom_quest,
                    'answer' => $request->custom_answer[$key],
                ]);
            }
        } else { 
            CustomQuestion::where('product_id', $product->id)->delete();
        }

         
        $request->lang = $request->lang ?? env('DEFAULT_LANGUAGE') ?? 'en';

        // Product Translations
        ProductTranslation::updateOrCreate(
            [
                'lang' => $request->lang,
                'product_id' => $product->id ?? $request->product_id
            ],
            $request->only([
                'name', 'unit', 'video_name', 'long_description', 'description'
            ])
        );
        
        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back();
    }
    public function destroy($id)
    {

        $product = Product::findOrFail($id);
        $product->product_translations()->delete();
        $product->stocks()->delete();
        $product->taxes()->delete();

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
    public function bulk_product_delete(Request $request)
    {

        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }
        return 1;
    }
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        $product_new = $product->replicate();
        $product_new->slug = $product_new->slug . '-' . Str::random(5);
        $product_new->save();

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        if ($request->type == 'In House')
            return redirect()->route('products.admin');
        elseif ($request->type == 'Seller')
            return redirect()->route('products.seller');
        elseif ($request->type == 'All')
            return redirect()->route('products.all');
    }
    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }
    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }
    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }
    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }
    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        $product->lastUpdated_featured = Carbon::now();
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }
    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;
        $cost_price = $request->cost_price;
        $wholesale_price = $request->wholesale_price;
        $wholesale_price_variant = $request->wholesale_price_variant;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                foreach ($request[$name] as $key => $item) {
                    // array_push($data, $item->value);
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = Combinations::makeCombinations($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'cost_price', 'wholesale_price', 'wholesale_price_variant', 'colors_active', 'product_name'));
    }
    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }
        $product_name = $request->name;
        $unit_price = $request->unit_price;
        $cost_price = $request->cost_price;
        $wholesale_price = $request->wholesale_price;
        $wholesale_price_variant = $request->wholesale_price_variant;
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                foreach ($request[$name] as $key => $item) {
                    // array_push($data, $item->value);
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = Combinations::makeCombinations($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'cost_price', 'wholesale_price', 'wholesale_price_variant', 'colors_active', 'product_name', 'product'));
    }
    public function getProductStock(Request $request)
    {
        $product = Product::with('stocks')->find($request->id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $stocks = $product->stocks;

        return response()->json(['product' => $product]);
    }
    public function create_barcode()
    {
        $products = Product::with('stocks')->get();
        return view('backend.product.products.create_barcode', compact('products'));
    }
    public function updateTrending(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->trending = $request->status;
        $product->save();
        Cache::forget('trending_products');
        return 1;
    }
    public function updateBestSelling(Request $request)
    {
        $product = Product::findOrFail($request->id);
        if ($product->best_selling == '0') {
            $validator = Validator::make([
                'best_selling_index' => $request->best_selling_index
            ], [
                'best_selling_index' => 'required|numeric'
                // 'best_selling_index'=>'required|numeric|unique:products,best_selling_index,' . $product->id
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors(), 'code' => 403]);
            }
        }
        $product->best_selling = $request->status;
        $product->best_selling_index = $request->best_selling_index ?? '0';
        $product->save();
        Cache::forget('best_selling_products');
        return response()->json(['code' => 200, 'status' => $request->status]);
    }
    public function getBestSelling(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $category_id = null;
        $products = Product::select('id', 'name', 'best_selling_index', 'best_selling', 'thumbnail_img', 'best_selling')->orderBy('created_at', 'desc')->where('published', 1)->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name ?? 'best_selling', $query ?? 'desc');
            $sort_type = $request->type;
        }


        if ($request->has('category_id') && $request->category_id != null) {
            $products = $products->where('category_id', $request->category_id);
            $category_id = $request->category_id;
        }
        $products = $products->where('best_selling', '1')->paginate(15);
        $type = 'All';
        return view('backend.product.best_selling.best_selling_index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search', 'category_id'));
    }
    public function updateProductBestSellingIndex(Request $request, Product $product)
    {
        $validated = $request->validate([
            'best_selling_index' => 'required|numeric|unique:products,best_selling_index,' . $product->id
        ]);
        $product->update([
            'best_selling_index' => $request->best_selling_index
        ]);
        flash(translate('Index updated successfully'))->success();
        return back();
    } 
}
