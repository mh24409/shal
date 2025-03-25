@php
    if (auth()->user() != null) {
        $user_id = Auth::user()->id;
        $cart = \App\Models\Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = Session()->get('temp_user_id');
        if ($temp_user_id) {
            $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        }
    }
    $cart_added = [];
    if (isset($cart) && count($cart) > 0) {
        $cart_added = $cart->pluck('product_id')->toArray();
    }
    if (count($product->stocks) > 1) {
        foreach ($product->stocks as $key => $stock) {
            if ($stock->default == 1) {
                $default_variation_img = get_product_stock_img($product->id, $stock->variant);
            }
        }
    }
    $dis = true;
    $defaultFounded = false;
    foreach ($product->stocks as $stock) {
        if ($stock->default == 1) {
            $defaultFounded = true;
            if ($stock->qty > 0) {
                $dis = true;
            } else {
                $dis = false;
            }
        }
    }
    if ($defaultFounded == false) {
        if ($product->stocks[0]->qty > 0) {
            $dis = true;
        } else {
            $dis = false;
        }
    }
@endphp
<div
    class="overflow-hidden  aiz-card-box aiz-card-box-{{ $product->id }} border-0 position-relative h-auto bg-white hov-scale-img d-flex justify-content-center flex-column">
    <img data-imgaFormVariation="{{ $uniqueID }}" width="70%" class="lazyload mx-auto has-transition"
        src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($product->thumbnail_img) }}"
        alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
    <div class="overflow-hidden" style="height: 65px;">
        <div class="absolute-bottom-left" style="transform: translateY(8px)">
            <video autoplay="true" loop="true" playsinline data-wf-ignore="true" data-object-fit="cover" muted="" style="width: 250px;" poster="">
                <source src="{{static_asset('uploads/all/'.$product->getTranslation('video_name'))}}"
                    type="video/mp4">
            </video>
        </div>
        <div class="absolute-bottom-right" style="right: 60px;">
            <button style="background-color: black"  onclick="showAddToCartModal('{{ $product->id }}')" class="btn text-white rounded-0 btn-sm w-150px">
                {{ translate('Quick Buy') }} </button>
        </div>
    </div>
</div>
