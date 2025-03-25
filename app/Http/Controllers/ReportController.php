<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Shop;
use App\Models\User;
use App\Models\View;
use App\Models\Order;
use App\Models\Search;
use App\Models\Wallet;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\CommissionHistory;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:in_house_product_sale_report'])->only('in_house_sale_report');
        $this->middleware(['permission:seller_products_sale_report'])->only('seller_sale_report');
        $this->middleware(['permission:products_stock_report'])->only('stock_report');
        $this->middleware(['permission:product_wishlist_report'])->only('wish_report');
        $this->middleware(['permission:user_search_report'])->only('user_search_report');
        $this->middleware(['permission:commission_history_report'])->only('commission_history');
        $this->middleware(['permission:wallet_transaction_report'])->only('wallet_transaction_history');
    }

    public function stock_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products', 'sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products', 'sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by = null;
        $sellers = Shop::with('user')->orderBy('created_at', 'desc');
        if ($request->has('verification_status')) {
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers', 'sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products', 'sort_by'));
    }

    public function user_search_report(Request $request)
    {
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    public function commission_history(Request $request)
    {
        $seller_id = null;
        $date_range = null;

        if (Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        }
        if ($request->seller_id) {
            $seller_id = $request->seller_id;
        }

        $commission_history = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id) {

            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }

        $commission_history = $commission_history->paginate(10);
        if (Auth::user()->user_type == 'seller') {
            return view('seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }

    public function wallet_transaction_history(Request $request)
    {
        $user_id = null;
        $date_range = null;

        if ($request->user_id) {
            $user_id = $request->user_id;
        }

        $users_with_wallet = User::whereIn('id', function ($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id) {
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }

        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }



    public function reports(Request $request)
    {
        $date = null;
        $startDate = null;
        $endDate = null;
        $dateRange = '';
        if ($request->input('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
                    break;
                case 'yesterday':
                    $startDate = date('Y-m-d 00:00:00', strtotime('yesterday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('yesterday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('yesterday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('yesterday'));

                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('last monday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('next sunday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('last monday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('next sunday'));

                    break;
                case 'month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date('Y-m-t 23:59:59');
                    $sort_date =  date('01-m-Y 00:00:00') . ' to ' . date('t-m-Y 23:59:59');

                    break;
                case 'year':
                    $startDate = date('Y-01-01 00:00:00');
                    $endDate = date('Y-12-31 23:59:59');
                    $sort_date =  date('01-01-Y 00:00:00') . ' to ' . date('12-31-Y 23:59:59');

                    break;
                default:
                    break;
            }
        } elseif (isset($request->date)) {
            $sort_date = $request->date;
            $startDate = explode(' to ', $sort_date)[0];
            $endDate = explode(' to ', $sort_date)[1];
            $startDate = \DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDate = \DateTime::createFromFormat('d-m-Y H:i:s', $endDate);
            // $startDate = $startDate->format('Y-m-d H:i:s');
            // $endDate = $endDate->format('Y-m-d H:i:s');
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
        }

        if (isset($date) || (isset($startDate) && isset($endDate))) {

            $lstartDate = strtotime($startDate);
            $lendDate = strtotime($endDate);
            // filter order by dates

            $dates = Order::select('id', DB::raw('FROM_UNIXTIME(date) as formatted_date'))
                ->whereBetween('date', [$lstartDate, $lendDate])
                ->whereHas('orderDetails', function ($query) {
                    $query->where('delivery_status', '!=', 'cancelled');
                })
                ->get();
            $datesIds = collect($dates)->pluck('id')->toArray();


            $products = OrderDetail::whereIn('id', $datesIds)->where('variation', "")->where('delivery_status', '!=', 'cancelled')->groupBy('product_id')->select('product_id', 'quantity', 'price')->get();
            $variations = OrderDetail::whereIn('id', $datesIds)->where('variation', "!=", "")->where('delivery_status', '!=', 'cancelled')->groupBy('product_id')->select('product_id', 'quantity', 'price')->get();


            $totalPrices = Order::select('id')
                ->whereIn('id', $datesIds)
                ->selectRaw('SUM(grand_total) as tPrices , COUNT(*) as numberOrders')
                ->get();
            $dataPrices = json_decode($totalPrices, true);
            $tPrices =  $totalPrices[0]->tPrices;
            $numberOrders =  $totalPrices[0]->numberOrders;

            // users
            $numberUsers = Order::selectRaw("
                   CASE WHEN phone LIKE '+2%' THEN SUBSTRING(phone, 3) ELSE phone END as normalized_phone,name,COUNT(*) as order_count,SUM(grand_total) as grand_total")
                ->whereIn('id', $datesIds)
                ->groupBy('normalized_phone', 'name')
                ->orderByDesc('grand_total')
                ->limit(9)
                ->get();

            // to get total shipping cost
            $shippingCost = OrderDetail::whereIn('order_id', $datesIds)
                ->selectRaw('SUM(shipping_cost) as ShippingCost')
                ->first();
            // product
            $order = OrderDetail::whereIn('order_id', $datesIds)
                ->where('delivery_status', '!=', 'cancelled')
                ->with(['product' => function ($query) {
                    $query->select('id', 'name', 'category_id', 'slug', 'thumbnail_img', 'rating');
                }])
                ->selectRaw('COUNT(*) as order_count, product_id , SUM(price) as total_price , SUM(shipping_cost) as shippingCost')
                ->groupBy('product_id')
                ->orderByDesc('order_count')
                ->limit(12)
                ->get();
            if (!empty($datesIds)) {
                $topProducts = DB::table('products')
                    ->select('products.name', 'top_products.total_sales', 'top_products.total_price')
                    ->join(DB::raw('(SELECT product_id, SUM(quantity) AS total_sales, SUM(price) AS total_price
                                        FROM order_details
                                        WHERE order_id IN (' . implode(',', $datesIds) . ')
                                        GROUP BY product_id
                                        ORDER BY total_sales DESC
                                        LIMIT 5) as top_products'), 'products.id', '=', 'top_products.product_id')
                    ->get();
            } else {
                // Handle the case when $datesIds is empty
                $topProducts = [];
            }

            $topCategories = DB::table('categories')
                ->select('categories.name', DB::raw('SUM(order_details.quantity) as total_quantity'), DB::raw('SUM(order_details.quantity * order_details.price) as total_price'))
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('order_details', 'products.id', '=', 'order_details.product_id')
                ->whereIn('order_details.order_id', $datesIds)
                ->groupBy('categories.id')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->get();





            //category
            $data = json_decode($order, true);
            $categories = [];
            if (!empty($data)) {
                $categories = [];
                foreach ($data as $item) {
                    if (isset($item['product']['category_id'])) {
                        $category_id = $item['product']['category_id'];
                        $category = Category::find($category_id);
                        if ($category) {
                            if (!isset($categories[$category_id])) {
                                $categories[$category_id] = [
                                    'id'         => $category->id,
                                    'name'       => $category->name,
                                    'totalPrice' => 0,
                                ];
                            }
                            $categories[$category_id]['totalPrice'] += $item['total_price'];
                        } else {
                            $categories[$category_id] = translate('Category Not Found');
                        }
                    }
                }
            }

            $categoriespric = collect($categories)->values()->map(function ($category, $index) {
                return [
                    'id' => $index + 1,
                    'name' => $category['name'],
                    'totalPrice' => $category['totalPrice']
                ];
            })->toArray();

            $productspric = collect($order)->values()->map(function ($order, $index) {
                return [
                    'id' => $index + 1,
                    'name' => $order['product']['name'] ?? translate('Not Found'),
                    'totalPrice' => $order['total_price']
                ];
            })->take(4)->toArray();

            $numberofviews =  View::selectRaw('url , COUNT(*) as numberViews')
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                // ->groupBy('url')
                ->orderByDesc('numberViews')
                ->get();

            $views = View::selectRaw("url, COUNT(*) as numberViews")
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->groupBy('url')
                ->orderByDesc('numberViews')
                ->get();


            $productviews = View::selectRaw("url")
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->get();

            $countProductUrls = $productviews->filter(function ($item) {
                return strpos($item->url, '/product') === 0;
            })->count();


            $resultviews = [];
            $viewsArray = json_decode($views, true);
            foreach ($viewsArray as $item) {
                $url = $item['url'];
                $resultviews[$url] = $item;
            }

            // return $sort_date;
            [
                $sort_date,
                $order,
                $startDate,
                $endDate,
                $dates,
                $tPrices,
                $numberOrders,
                $shippingCost,
                $numberUsers,
                $categories,
            ];
            return view('backend.reports.home_reports', compact(
                'dateRange',
                'sort_date',
                'order',
                'startDate',
                'endDate',
                'dates',
                'tPrices',
                'numberOrders',
                'shippingCost',
                'numberUsers',
                'categories',
                'resultviews',
                'numberofviews',
                'countProductUrls',
                'categoriespric',
                'productspric',
                'products',
                'variations'
            ));
        }
        return view('backend.reports.home_reports');
    }
    public function showproduct($id, $sort_date)
    {
        // return $sort_date;
        $products = Product::find($id);
        $startDate = explode(' to ', $sort_date)[0];
        $endDate = explode(' to ', $sort_date)[1];


        $lstartDate = strtotime($startDate);
        $startDate = date("m-d-Y", $lstartDate);

        $lendDate = strtotime($endDate);
        $endDate = date("m-d-Y", $lendDate);

        // $startDate = \DateTime::createFromFormat('m-d-Y H:i:s', $startDate);
        // $endDate = \DateTime::createFromFormat('m-d-Y H:i:s', $endDate);
        // $startDate = $startDate->format('Y-m-d H:i:s');
        // $endDate = $endDate->format('Y-m-d H:i:s');
        // $lstartDate = strtotime($startDate);
        // $lendDate = strtotime($endDate);
        //dates
        $dates = Order::select('id', DB::raw('FROM_UNIXTIME(date) as formatted_date'))
            ->whereBetween('date', [$lstartDate, $lendDate])
            ->whereHas('orderDetails', function ($query) {
                $query->where('delivery_status', '!=', 'cancelled');
            })
            ->get();
        $datesIds = collect($dates)->pluck('id')->toArray();
        $datesIds = collect($dates)->pluck('id')->toArray();
        //orders
        $orders = OrderDetail::where('product_id', '=', $id)
            ->whereIn('order_id', $datesIds)
            ->where('delivery_status', '!=', 'cancelled')
            ->get();
        return view('backend.newblades.showproduct', compact('products', 'orders'));
    }
    public function pagesView($sort_date  = null, Request $request)
    {
        $startDate = null;
        $endDate = null;
        $dateRange = null;

        if ($request->input('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
                    break;
                case 'yesterday':
                    $startDate = date('Y-m-d 00:00:00', strtotime('yesterday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('yesterday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('yesterday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('yesterday'));

                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('last monday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('next sunday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('last monday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('next sunday'));

                    break;
                case 'month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date('Y-m-t 23:59:59');
                    $sort_date =  date('01-m-Y 00:00:00') . ' to ' . date('t-m-Y 23:59:59');

                    break;
                case 'year':
                    $startDate = date('Y-01-01 00:00:00');
                    $endDate = date('Y-12-31 23:59:59');
                    $sort_date =  date('01-01-Y 00:00:00') . ' to ' . date('12-31-Y 23:59:59');

                    break;
                default:
                    break;
            }
        } elseif (isset($request->date)) {
            $sort_date = $request->date;
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
        }
        if ($sort_date) {
            $startDate = explode(' to ', $sort_date)[0];
            $endDate = explode(' to ', $sort_date)[1];
            $startDate = \DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDate = \DateTime::createFromFormat('d-m-Y H:i:s', $endDate);
        }
        $views =  View::selectRaw('url , COUNT(*) as numberViews')
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->groupBy('url')
            ->orderByDesc('numberViews')
            ->get();

        $totalProViews = $views->filter(function ($item) {
            return strpos($item->url, '/pro') === 0;
        })->sum('numberViews');


        return view('backend.newblades.pagesview', compact('views', 'dateRange', 'totalProViews'));
    }
    public function category_reports(Request $request)
    {
        $date = null;
        $startDate = null;
        $endDate = null;
        $dateRange = null;
        if ($request->input('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
                    break;
                case 'yesterday':
                    $startDate = date('Y-m-d 00:00:00', strtotime('yesterday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('yesterday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('yesterday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('yesterday'));

                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('last monday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('next sunday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('last monday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('next sunday'));

                    break;
                case 'month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date('Y-m-t 23:59:59');
                    $sort_date =  date('01-m-Y 00:00:00') . ' to ' . date('t-m-Y 23:59:59');

                    break;
                case 'year':
                    $startDate = date('Y-01-01 00:00:00');
                    $endDate = date('Y-12-31 23:59:59');
                    $sort_date =  date('01-01-Y 00:00:00') . ' to ' . date('12-31-Y 23:59:59');

                    break;
                default:
                    break;
            }
        } elseif (isset($request->date)) {
            $sort_date = $request->date;
            $startDate = explode(' to ', $sort_date)[0];
            $endDate = explode(' to ', $sort_date)[1];
            $startDate = \DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDate = \DateTime::createFromFormat('d-m-Y H:i:s', $endDate);
            // $startDate = $startDate->format('Y-m-d H:i:s');
            // $endDate = $endDate->format('Y-m-d H:i:s');
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
        }

        if (isset($date) || (isset($startDate) && isset($endDate))) {

            $lstartDate = strtotime($startDate);
            $lendDate = strtotime($endDate);
            // filter order by dates
            $dates = Order::select('id', DB::raw('FROM_UNIXTIME(date) as formatted_date'))
                ->whereBetween('date', [$lstartDate, $lendDate])
                ->whereHas('orderDetails', function ($query) {
                    $query->where('delivery_status', '!=', 'cancelled');
                })
                ->get();



            $datesIds = collect($dates)->pluck('id')->toArray();
            $totalPrices = Order::select('id')
                ->whereIn('id', $datesIds)
                ->selectRaw('SUM(grand_total) as tPrices , COUNT(*) as numberOrders')
                ->get();
            $dataPrices = json_decode($totalPrices, true);
            $tPrices =  $totalPrices[0]->tPrices;
            $numberOrders =  $totalPrices[0]->numberOrders;

            $numberUsers = Order::selectRaw("
                   CASE WHEN phone LIKE '+2%' THEN SUBSTRING(phone, 3) ELSE phone END as normalized_phone,name,COUNT(*) as order_count,SUM(grand_total) as grand_total")
                ->whereIn('id', $datesIds)
                ->groupBy('normalized_phone', 'name')
                ->orderByDesc('grand_total')
                ->get();

            // to get total shipping cost
            $shippingCost = OrderDetail::whereIn('order_id', $datesIds)
                ->selectRaw('SUM(shipping_cost) as ShippingCost')
                ->first();
            // product
            $order = OrderDetail::whereIn('order_id', $datesIds)
                ->where('delivery_status', '!=', 'cancelled')
                ->with(['product' => function ($query) {
                    $query->select('id', 'name', 'category_id', 'slug', 'thumbnail_img', 'rating');
                }])
                ->selectRaw('COUNT(*) as order_count, product_id , SUM(price) as total_price , SUM(shipping_cost) as shippingCost')
                ->groupBy('product_id')
                ->orderByDesc('order_count')
                ->get();
            //category
            $data = json_decode($order, true);
            $categories = [];
            if (!empty($data)) {
                $categories = [];
                foreach ($data as $item) {
                    if (isset($item['product']['category_id'])) {
                        $category_id = $item['product']['category_id'];
                        $category = Category::find($category_id);

                        if ($category) {
                            if (!isset($categories[$category_id])) {
                                $categories[$category_id] = [
                                    'id'         => $category->id,
                                    'name'       => $category->name,
                                    'totalPrice' => 0,
                                ];
                            }
                            $categories[$category_id]['totalPrice'] += $item['total_price'];
                        } else {
                            $categories[$category_id] = translate('Category Not Found');
                        }
                    }
                }
            }
            /////////////////////////////////////////////
            $totalProductNumberForAllCategories  = count($categories);

            $totalPriceForAllCategories = 0;
            foreach ($categories as $category) {
                $totalPriceForAllCategories += $category['totalPrice'];
            }

            $totalOrderForAllCategories = $order->groupBy('product.category_id')->map(function ($categoryOrders) {
                return [
                    'category_id' => $categoryOrders->first()->product->category_id ?? translate('Not Found'),
                    'category_name' => $categoryOrders->first()->product->category->name ?? translate('Not Found'),
                    'order_count' => $categoryOrders->count(),
                ];
            });

            $categoryProductCounts = $order->groupBy('product.category_id')->map(function ($categoryOrders) {
                return [
                    'category_id' => $categoryOrders->first()->product->category_id ?? translate('Not Found'),
                    'category_name' => $categoryOrders->first()->product->category->name ?? translate('Not Found'),
                    'product_count' => $categoryOrders->sum('order_count'),
                ];
            });

            $totalOrderNumberFllCategories = 0;
            foreach ($totalOrderForAllCategories as $category) {
                $totalOrderNumberFllCategories += $category['order_count'];
            }

            $totalProductFllCategories = 0;
            foreach ($categoryProductCounts as $category) {
                $totalProductFllCategories += $category['product_count'];
            }




            $numberofviews =  View::selectRaw('url , COUNT(*) as numberViews')
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                // ->groupBy('url')
                ->orderByDesc('numberViews')
                ->get();

            $views = View::selectRaw("url, COUNT(*) as numberViews")
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->groupBy('url')
                ->orderByDesc('numberViews')
                ->get();


            $productviews = View::selectRaw("url")
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->get();

            $countProductUrls = $productviews->filter(function ($item) {
                return strpos($item->url, '/product') == 0;
            })->count();


            $resultviews = [];
            $viewsArray = json_decode($views, true);
            foreach ($viewsArray as $item) {
                $url = $item['url'];
                $resultviews[$url] = $item;
            }

            [
                $sort_date,
                $order,
                $startDate,
                $endDate,
                $dates,
                $tPrices,
                $numberOrders,
                $shippingCost,
                $numberUsers,
                $categories,
                $countProductUrls
            ];
            // return $sort_date;
            return view('backend.reports.site_reports.category_reports', compact(
                'sort_date',
                'order',
                'startDate',
                'endDate',
                'dates',
                'tPrices',
                'numberOrders',
                'shippingCost',
                'numberUsers',
                'categories',
                'countProductUrls',
                'totalProductNumberForAllCategories',
                'totalPriceForAllCategories',
                'totalOrderForAllCategories',
                'categoryProductCounts',
                'categoryProductCounts',
                'totalProductFllCategories',
                'totalOrderNumberFllCategories',
                'dateRange',

            ));
        }
        return view('backend.reports.category_reports', compact('sort_date'));
    }
    public function products_reports(Request $request)
    {
        $date = null;
        $startDate = null;
        $dateRange = null;
        $endDate = null;
        if ($request->input('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
                    break;
                case 'yesterday':
                    $startDate = date('Y-m-d 00:00:00', strtotime('yesterday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('yesterday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('yesterday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('yesterday'));

                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('last monday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('next sunday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('last monday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('next sunday'));

                    break;
                case 'month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date('Y-m-t 23:59:59');
                    $sort_date =  date('01-m-Y 00:00:00') . ' to ' . date('t-m-Y 23:59:59');

                    break;
                case 'year':
                    $startDate = date('Y-01-01 00:00:00');
                    $endDate = date('Y-12-31 23:59:59');
                    $sort_date =  date('01-01-Y 00:00:00') . ' to ' . date('31-12-Y 23:59:59');

                    break;
                default:
                    break;
            }
        } elseif (isset($request->date)) {

            $sort_date = $request->date;
            $startDate = explode(' to ', $sort_date)[0];
            $endDate = explode(' to ', $sort_date)[1];
            $startDate = \DateTime::createFromFormat('d-m-Y H:i:s', $startDate)->format('Y-m-d H:i:s');
            $endDate = \DateTime::createFromFormat('d-m-Y H:i:s', $endDate)->format('Y-m-d H:i:s');
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
        }
        if (isset($date) || (isset($startDate) && isset($endDate))) {
            $lstartDate = strtotime($startDate);
            $lendDate = strtotime($endDate);
            $dates = Order::select('id', DB::raw('FROM_UNIXTIME(date) as formatted_date'))
                ->whereBetween('date', [$lstartDate, $lendDate])
                ->whereHas('orderDetails', function ($query) {
                    $query->where('delivery_status', '!=', 'cancelled');
                })
                ->get();
            $datesIds = collect($dates)->pluck('id')->toArray();

            // total prices and total otrders
            $totalPrices = Order::select('id')
                ->whereIn('id', $datesIds)
                ->selectRaw('SUM(grand_total) as tPrices , COUNT(*) as numberOrders')
                ->get();
            $dataPrices = json_decode($totalPrices, true);
            $tPrices =  $totalPrices[0]->tPrices;
            $numberOrders =  $totalPrices[0]->numberOrders;
            // users
            $numberUsers = Order::selectRaw("
                   CASE WHEN phone LIKE '+2%' THEN SUBSTRING(phone, 3) ELSE phone END as normalized_phone,name,COUNT(*) as order_count,SUM(grand_total) as grand_total")
                ->whereIn('id', $datesIds)
                ->groupBy('normalized_phone', 'name')
                ->orderByDesc('grand_total')
                ->limit(9)
                ->get();

            // to get total shipping cost
            $shippingCost = OrderDetail::whereIn('order_id', $datesIds)
                ->selectRaw('SUM(shipping_cost) as ShippingCost')
                ->first();
            // product
            $order = OrderDetail::whereIn('order_id', $datesIds)
                ->where('delivery_status', '!=', 'cancelled')
                ->with(['product' => function ($query) {
                    $query->select('id', 'name', 'category_id', 'slug', 'thumbnail_img', 'rating');
                }])
                ->selectRaw('COUNT(*) as order_count, product_id , SUM(price) as total_price , SUM(shipping_cost) as shippingCost')
                ->groupBy('product_id')
                ->orderByDesc('order_count')
                ->get();

            $topProducts = DB::table('products')
                ->select(
                    'products.name',
                    'products.id as product_id',
                    'top_products.total_sales',
                    'categories.name as category_name',
                    'top_products.total_price',
                    DB::raw('COUNT(DISTINCT order_details.order_id) AS order_count'),
                    DB::raw('SUM(order_details.quantity) AS total_items_sold')
                )
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join(DB::raw('(SELECT product_id, SUM(quantity) AS total_sales, SUM(quantity * price) AS total_price
                    FROM order_details
                    WHERE order_id IN (' . implode(',', $datesIds) . ')
                    GROUP BY product_id
                    ORDER BY total_sales DESC
                    LIMIT 5) as top_products'), 'products.id', '=', 'top_products.product_id')
                ->join('order_details', 'products.id', '=', 'order_details.product_id')
                ->whereNotIn('order_details.delivery_status', ['cancelled', 'refunded']);
             
            if (!empty($datesIds)) {
                $topProducts = $topProducts->get();
            } else {
                $topProducts = [];
            } 
            $data = json_decode($order, true);
            $categories = [];
            if (!empty($data)) {
                $categories = [];
                foreach ($data as $item) {
                    if (isset($item['product']['category_id'])) {
                        $category_id = $item['product']['category_id'];
                        $category = Category::find($category_id);

                        if ($category) {
                            if (!isset($categories[$category_id])) {
                                $categories[$category_id] = [
                                    'id'         => $category->id,
                                    'name'       => $category->name,
                                    'totalPrice' => 0,
                                ];
                            }
                            $categories[$category_id]['totalPrice'] += $item['total_price'];
                        } else {
                            $categories[$category_id] = "Category not found";
                        }
                    }
                }
            }

            $numberofviews =  View::selectRaw('url , COUNT(*) as numberViews')
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                // ->groupBy('url')
                ->orderByDesc('numberViews')
                ->get();

            $views = View::selectRaw("url, COUNT(*) as numberViews")
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->groupBy('url')
                ->orderByDesc('numberViews')
                ->get();


            $productviews = View::selectRaw("url")
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->get();

            $countProductUrls = $productviews->filter(function ($item) {
                return strpos($item->url, '/product') == 0;
            })->count();
            $resultviews = [];
            $viewsArray = json_decode($views, true);
            foreach ($viewsArray as $item) {
                $url = $item['url'];
                $resultviews[$url] = $item;
            }
            $totalProduct = count($order);
            $totalOrder = 0;
            foreach ($order as $iten) {
                $totalOrder += $iten['order_count'];
            }

            $totalPrice = 0;
            foreach ($order as $iten) {
                $totalPrice += $iten['total_price'];
            }

            // return $sort_date;
            [
                $sort_date,
                $order,
                $startDate,
                $endDate,
                $dates,
                $tPrices,
                $numberOrders,
                $shippingCost,
                $numberUsers,
                $categories,
            ];
            // return $sort_date;
            return view('backend.reports.site_reports.product_report', compact(
                'sort_date',
                'order',
                'startDate',
                'endDate',
                'dates',
                'tPrices',
                'numberOrders',
                'shippingCost',
                'numberUsers',
                'categories',
                'countProductUrls',
                'totalProduct',
                'totalOrder',
                'dateRange',
                'totalPrice',
                'topProducts'
            ));
        }
    }

    public function order_reports(Request $request)
    {
        $date = null;
        $startDate = null;
        $endDate = null;
        $dateRange = '';
        if ($request->input('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
                    break;
                case 'yesterday':
                    $startDate = date('Y-m-d 00:00:00', strtotime('yesterday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('yesterday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('yesterday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('yesterday'));

                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('last monday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('next sunday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('last monday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('next sunday'));

                    break;
                case 'month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date('Y-m-t 23:59:59');
                    $sort_date =  date('01-m-Y 00:00:00') . ' to ' . date('t-m-Y 23:59:59');

                    break;
                case 'year':
                    $startDate = date('Y-01-01 00:00:00');
                    $endDate = date('Y-12-31 23:59:59');
                    $sort_date =  date('01-01-Y 00:00:00') . ' to ' . date('12-31-Y 23:59:59');

                    break;
                default:
                    break;
            }
        } elseif (isset($request->date)) {
            $sort_date = $request->date;
            $startDate = explode(' to ', $sort_date)[0];
            $endDate = explode(' to ', $sort_date)[1];
            $startDate = \DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDate = \DateTime::createFromFormat('d-m-Y H:i:s', $endDate);
            $startDate = $startDate->format('Y-m-d H:i:s');
            $endDate = $endDate->format('Y-m-d H:i:s');
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
        }
        $lstartDate = strtotime($startDate);
        $lendDate = strtotime($endDate);
        $datesIds = Order::whereBetween('date', [$lstartDate, $lendDate])->whereHas('orderDetails', function ($query) {
            $query->where('delivery_status', '!=', 'cancelled');
        })->pluck('id')->toArray();

        $orders = Order::select(
            'orders.date',
            'orders.coupon_code',
            'orders.id as order_id',
            'orders.code',
            'users.name',
            'orders.delivery_status',
            'orders.payment_type',
            DB::raw('SUM(order_details.quantity * order_details.price) AS total_net_price'),
            DB::raw('SUM(orders.grand_total) AS grand_total')

        )
            ->whereIn('orders.id', $datesIds)
            ->whereHas('orderDetails', function ($query) {
                $query->where('delivery_status', '!=', 'cancelled');
            })
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->groupBy('orders.id')
            ->get();

        $customerIds = $orders->pluck('user_id')->toArray();
        $customerTypes = [];

        foreach ($customerIds as $customerId) {
            $customerType = Order::where('user_id', $customerId)
                ->where('id', '!=', $customerId)
                ->exists() ? 'returning' : 'new';
            $customerTypes[$customerId] = $customerType;
        }
        $orders->each(function ($order) use ($customerTypes) {
            $order->customer_type = $customerTypes[$order->user_id] ?? 'new';
            $order->products = OrderDetail::select(
                'products.name as product_name',
                'products.id as product_id',
                'order_details.quantity as product_quantity',
                DB::raw('SUM(order_details.quantity * order_details.price) AS net_price')
            )
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->where('order_id', $order->order_id)
                ->groupBy('products.id')
                ->get();
        });
        $totalNetSales = Order::whereIn('id', $datesIds)
            ->whereHas('orderDetails', function ($query) {
                $query->where('delivery_status', '!=', 'cancelled');
            })->sum('grand_total');
        foreach ($orders as $order) {
            $order->products_count = $order->order_details_count;
        }
        $totalProductCount = 0;

        foreach ($orders as $order) {
            $totalProductCount += $order->products->count();
        }
        $filter_type = $request->filter_type;
        if ($filter_type) {
            $filter_orders = [];
            switch ($filter_type) {
                case 'returning_customers':
                    foreach ($orders as $order) {
                        if ($order->customer_type == 'returning') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                case 'new_customers':
                    foreach ($orders as $order) {
                        if ($order->customer_type == 'new') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                case 'cod_orders':
                    foreach ($orders as $order) {
                        if ($order->payment_type == 'cash_on_delivery') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                case 'cod_completed_orders':
                    foreach ($orders as $order) {
                        if ($order->payment_type == 'cash_on_delivery' && $order['delivery_status'] == 'completed') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                case 'cod_uncompleted_orders':
                    foreach ($orders as $order) {
                        if ($order->payment_type == 'cash_on_delivery' && $order['delivery_status'] != 'completed') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                case 'tamara_orders':
                    foreach ($orders as $order) {
                        if ($order->payment_type == 'tamara') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                case 'edfaa_orders':
                    foreach ($orders as $order) {
                        if ($order->payment_type == 'edfa3') {
                            $filter_orders[] = $order;
                        }
                    }
                    break;
                default:
                    $filter_orders = $orders;
                    break;
            }
            $orders = $filter_orders;
        }

        return view('backend.reports.order_report', compact('orders','filter_type', 'sort_date', 'dateRange', 'totalProductCount'));
    }

   public function profitReport(Request $request)
    {
        $date = null;
        $startDate = null;
        $endDate = null;
        $dateRange = '';
        if ($request->input('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
                    break;
                case 'yesterday':
                    $startDate = date('Y-m-d 00:00:00', strtotime('yesterday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('yesterday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('yesterday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('yesterday'));

                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('last monday'));
                    $endDate = date('Y-m-d 23:59:59', strtotime('next sunday'));
                    $sort_date =  date('d-m-Y 00:00:00', strtotime('last monday')) . ' to ' . date('d-m-Y 23:59:59', strtotime('next sunday'));

                    break;
                case 'month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date('Y-m-t 23:59:59');
                    $sort_date =  date('01-m-Y 00:00:00') . ' to ' . date('t-m-Y 23:59:59');

                    break;
                case 'year':
                    $startDate = date('Y-01-01 00:00:00');
                    $endDate = date('Y-12-31 23:59:59');
                    $sort_date =  date('01-01-Y 00:00:00') . ' to ' . date('12-31-Y 23:59:59');

                    break;
                default:
                    break;
            }
        } elseif (isset($request->date)) {
            $sort_date = $request->date;
            $startDate = explode(' to ', $sort_date)[0];
            $endDate = explode(' to ', $sort_date)[1];
            $startDate = \DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDate = \DateTime::createFromFormat('d-m-Y H:i:s', $endDate);
            $startDate = $startDate->format('Y-m-d H:i:s');
            $endDate = $endDate->format('Y-m-d H:i:s');
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $sort_date =  date('d-m-Y 00:00:00') . ' to ' . date('d-m-Y 23:59:59');
        }
        $lstartDate = strtotime($startDate);
        $lendDate = strtotime($endDate);
        $datesIds = Order::whereBetween('date', [$lstartDate, $lendDate])
            ->whereHas('orderDetails', function ($query) {
                $query->where('delivery_status', '!=', 'cancelled');
            })
            ->pluck('id')->toArray();
        $orders = Order::select(
            'orders.date',
            'orders.id',
            'orders.code',
            DB::raw('SUM(orders.grand_total) AS grand_total'),
            DB::raw('SUM(orders.coupon_discount) AS coupon_discount'),
            DB::raw('SUM(order_details.price * order_details.quantity) AS net_sales'),
            DB::raw('SUM(order_details.tax) AS tax'),
            DB::raw('SUM(orders.COD_tax) AS COD_tax'),
            DB::raw('SUM(orders.shipping_fees) AS shipping_fees'),
            DB::raw('SUM(orders.shipping_cost) AS shipping_cost'),
            DB::raw('SUM(order_details.cost_price * order_details.quantity) AS total_cost_price'),
            DB::raw('COUNT(orders.id) AS order_count')
        )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereIn('orders.id', $datesIds)

            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(24);
        $total_profit = 0;
        $total_grand = 0;
        $total_cost_price = 0;
        foreach ($orders as $order) {
            $order_profit =   $order->grand_total - ($order->coupon_discount + $order->total_cost_price + $order->tax + $order->shipping_fees);
            $total_profit += $order_profit;
            $total_grand += $order->grand_total;
            $total_cost_price += $order->total_cost_price;
        }
        return view('backend.reports.partials.ProfitReport', compact('orders', 'total_grand', 'total_cost_price', 'total_profit', 'sort_date', 'dateRange'));
    }
}
