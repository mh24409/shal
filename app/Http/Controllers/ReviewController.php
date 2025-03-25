<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CustomReviewStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class ReviewController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:view_product_reviews'])->only('index');
        $this->middleware(['permission:publish_product_review'])->only('updatePublished');
    }
    public function index(Request $request)
    {
        $reviews = Review::query();
        $allowed_rates = [
            'rating,desc',
            'rating,asc'
        ];
        if(in_array($request->rating,$allowed_rates)){
            $reviews->orderBy('rating', explode(",",$request->rating)[1]);
        }
        if($request->reveiws_type >= 0){
            $reviews->where('custom', $request->reveiws_type);
        }else{
            $reviews = $reviews->where('custom','0');
        }
        $reviews = $reviews->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.product.reviews.index', compact('reviews'));
    }
public function createCustomReviews(Request $request)
    {
        return view('backend.product.reviews.add_custom_reviews');
    }


    public function customProductReview(CustomReviewStoreRequest $request){

        $comments = [];
        collect(json_decode($request->comments,true))->whereNotNull('value')
        ->map(function ($comment) use(&$comments) {
            $comments[] = $comment['value'];
        });
        $names = [];
        collect(json_decode($request->names,true))->whereNotNull('value')
        ->map(function ($name) use(&$names,$comments) {
            $names[$name['value']] = $comments;
        });

        $status = false ;

        if(count($request->categories ?? []) > 0)
        {
            $products = Product::select('id','created_at')->whereIn('category_id',$request->categories)->get();
            $status =  $this->generateReview($products,$names);
        }

        if(count($request->products ?? []) > 0)
        {
            $products = Product::select('id','created_at')->whereIn('id',$request->products)->get();
            $status = $this->generateReview($products,$names);
        }

        if($status)
        {
            flash(translate('Reviews added successfully'))->success();
        }else
        {
            flash(translate('Action Failed'))->error();
        }
        return redirect()->back();
    }

    public function generateReview($products,$names){
        $request = request();
        $dateRang = explode('to',$request->date_rang);
        if(!isset($dateRang[0]) || !isset($dateRang[1])){
            flash(translate('Date Rang Is Not Correct'))->error();
            return false;
        }
        $countNames = count($names);
        $customSerialCode = 'CRS-' . strtoupper(Str::random(8));
        $namesOnly = array_keys($names);
        $limit = $request->limit != null && $request->limit <= count($namesOnly)  ? $request->limit : count($namesOnly);
        $collection = collect($products)->map(function ($product)  use(&$names,$countNames,$namesOnly,$dateRang,$limit,$customSerialCode) {
            for ($i = 0; $i < $limit; $i++)
            {
                $nameCommentIndex = rand(0,count($names[$namesOnly[$i]]) - 1);
                if(!isset($names[$namesOnly[$i]][$nameCommentIndex])){
                    continue;
                }
                $review = new Review();
                $review->product_id = $product->id;
                // $review->photos = $request->image;
                $review->comment = $names[$namesOnly[$i]][$nameCommentIndex];
                $review->username = $namesOnly[$i];
                // $review->rating = $request->rate;
                $review->rating = 5;
                $review->custom = '1';
                $review->custom_serial_code = $customSerialCode;
                $review->created_at = date('Y-m-d H:i:s',mt_rand(Carbon::parse($dateRang[0])->timestamp,Carbon::parse($dateRang[1])->timestamp));
                $review->save();
                $product->rating = Review::where('product_id', $product->id)->where('status', 1)->avg('rating');
                $product->save();
                unset($names[$namesOnly[$i]][$nameCommentIndex]);
            }
        });
        return true;
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $review = new Review;
        $review->product_id = $request->product_id;
        $review->user_id = Auth::user()->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->photos = implode(',', $request->photos);
        $review->viewed = '0';
        $review->save();
        $product = Product::findOrFail($request->product_id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        if($product->added_by == 'seller'){
            $seller = $product->user->shop;
            $seller->rating = (($seller->rating*$seller->num_of_reviews)+$review->rating)/($seller->num_of_reviews + 1);
            $seller->num_of_reviews += 1;
            $seller->save();
        }

        flash(translate('Review has been submitted successfully'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        flash(translate('review has been deleted successfully'))->success();
        return redirect()->back();
    }

    public function updatePublished(Request $request)
    {
        $review = Review::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        $product = Product::findOrFail($review->product->id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        if($product->added_by == 'seller'){
            $seller = $product->user->shop;
            if ($review->status) {
                $seller->rating = (($seller->rating*$seller->num_of_reviews)+$review->rating)/($seller->num_of_reviews + 1);
                $seller->num_of_reviews += 1;
            }
            else {
                $seller->rating = (($seller->rating*$seller->num_of_reviews)-$review->rating)/max(1, $seller->num_of_reviews - 1);
                $seller->num_of_reviews -= 1;
            }

            $seller->save();
        }

        return 1;
    }

    public function product_review_modal(Request $request){
        $product = Product::where('id',$request->product_id)->first();
        $review = Review::where('user_id',Auth::user()->id)->where('product_id',$product->id)->first();
        return view('frontend.user.product_review_modal', compact('product','review'));
    }
    public function removeCustomReviews(Request $request)
    {
        $serialCode = $request->serial_code;
        if(empty($serialCode)){
            flash(translate('serial code cant be empty'))->error();
            return redirect()->back();
        }
        if(count($request->categories ?? []) > 0)
        {
            Product::select('id')->with('reviews')->whereIn('category_id',$request->categories)->get()
            ->map(function ($product) use($serialCode) {
                $product->reviews()->where('custom_serial_code',$serialCode)->delete();
            });
        }

        if(count($request->products ?? []) > 0)
        {
            Review::where('custom_serial_code',$serialCode)->whereIn('product_id',$request->products)->delete();
        }

        if(count($request->categories ?? []) > 0 || count($request->products ?? []) > 0)
        {
            flash(translate('Custom reviews removed successfully'))->success();
        }else
        {
            Review::where('custom_serial_code',$serialCode)->delete();
            flash(translate('Custom reviews removed successfully'))->success();
        }
        return redirect()->back();
    }
}
