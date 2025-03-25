<?php
namespace App\Models;
use App;
use App\Models\Color;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = ['choice_attributes'];

    protected $with = ['product_translations', 'taxes','questions'];
    protected $appends = [
        'default_variation',
         'sub_categories'
    ];
    protected $casts = [
        'min_qty'=>'integer',
        'sub_category_id' =>'array'
    ];


    public function getAllStocksQtyAttribute()
    {
        $stocks_qty = 0;
        if (isset($this->stocks)) {
            if (count($this->stocks) > 0) {

                foreach ($this->stocks as $key => $stock) {
                    $stocks_qty = $stocks_qty + $stock->qty;
                }
            }
        }
        return  $stocks_qty;
    }



    public function getDefaultVariationAttribute()
    {
        $elements = [];
        if (isset($this->stocks)) {
            if (count($this->stocks) > 0) {
                $str2 = '';
                $defaultDefined = false;
                foreach ($this->stocks as $key => $stock) {
                    if ($stock->default == 1) {
                        $str2 = $stock->variant;
                        $defaultDefined = true;
                    }
                }
                if (!$defaultDefined) {
                    $str2 = $this->stocks[0]->variant;
                }
                $parts = explode('-', $str2);
                foreach ($parts as $index => $part) {
                    if ($index === 0) {
                        if (isset($this->color)) {
                            if ($this->colors && count(json_decode($this->color)) > 0) {
                                $color_code = Color::where('name', $part)->first()->code;
                                $elements[] = $color_code;
                            }
                        } else {
                            $elements[] = $part;
                        }
                    } else {
                        $elements[] = str_replace(' ', '', $part);
                    }
                }
            }
        }
        return  $elements;
    }
    
    
    
    public function getSubCategoriesAttribute(){
        $sub_categories = [];
        foreach(explode(",", $this->sub_category_id) as $cat){
            $category = Category::find($cat);
            if ($category) {
                $sub_categories[] = $category;
            }
        }
        return $sub_categories;
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }

    public function scopeDigital($query)
    {
        return $query->where('digital', 1);
    }
    public function questions()
    {
        return $this->belongsToMany(QuestionAnswer::class);
    }
    public function custom_questions()
    {
        return $this->belongsToMany(CustomQuestion::class);
    }
}
