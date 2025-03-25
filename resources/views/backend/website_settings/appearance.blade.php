@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('General') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Frontend Website Name') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="website_name">
                                <input type="text" name="website_name" class="form-control"
                                    placeholder="{{ translate('Website Name') }}" value="{{ get_setting('website_name') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('allowed free shipping values') }}</label>
                            <div class="col-md-8 d-flex justify-content-between">
                                <input type="hidden" name="types[]" value="allwed_free_shipping_discount">
                                <input type="hidden" name="types[]" value="allowed_free_shipping_quantity">
                                <input type="text" name="allwed_free_shipping_discount" class="form-control col-5"
                                    placeholder="{{ translate('allowed free shipping price') }}"
                                    value="{{ get_setting('allwed_free_shipping_discount') }}">
                                <input type="text" name="allowed_free_shipping_quantity" class="form-control col-5"
                                    placeholder="{{ translate('allowed free shipping quantity') }}"
                                    value="{{ get_setting('allowed_free_shipping_quantity') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('payment method banner') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="payment_method_text">
                                <input type="text" name="payment_method_text" class="form-control w-100 mb-2"
                                    placeholder="{{ translate('payment method text') }}"
                                    value="{{ get_setting('payment_method_text') }}">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="payment_method_icon">
                                    <input type="hidden" name="payment_method_icon"
                                        value="{{ get_setting('payment_method_icon') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('News Bar Color') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="news_bar_color">
                                <input type="text" name="news_bar_color" class="form-control"
                                    placeholder="{{ translate('News Bar Color') }}"
                                    value="{{ get_setting('news_bar_color') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('News Bar Font Color') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="news_bar_font_color">
                                <input type="text" name="news_bar_font_color" class="form-control"
                                    placeholder="{{ translate('News Bar Font Color') }}"
                                    value="{{ get_setting('news_bar_font_color') }}">
                            </div>
                        </div>
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">{{ translate('Product Details Payment Methods Widget ') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ translate('Product Details Payment Methods') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="product_payment_method">
                                        <input type="hidden" name="product_payment_method" class="selected-files"
                                            value="{{ get_setting('product_payment_method') }}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Site Motto') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="site_motto">
                                <input type="text" name="site_motto" class="form-control"
                                    placeholder="{{ translate('Best eCommerce Website') }}"
                                    value="{{ get_setting('site_motto') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Site Icon') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="site_icon">
                                    <input type="hidden" name="site_icon" value="{{ get_setting('site_icon') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                                <small class="text-muted">{{ translate('Website favicon. 32x32 .png') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Color') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="base_color">
                                <input type="text" name="base_color" class="form-control" placeholder="#377dff"
                                    value="{{ get_setting('base_color') }}">
                                <small class="text-muted">{{ translate('Hex Color Code') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Website Base Hover Color') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="base_hov_color">
                                <input type="text" name="base_hov_color" class="form-control" placeholder="#377dff"
                                    value="{{ get_setting('base_hov_color') }}">
                                <small class="text-muted">{{ translate('Hex Color Code') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Customer Login page Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="login_page_image">
                                    <input type="hidden" name="login_page_image"
                                        value="{{ get_setting('login_page_image') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Customer Register page Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="register_page_image">
                                    <input type="hidden" name="register_page_image"
                                        value="{{ get_setting('register_page_image') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        @if (get_setting('vendor_system_activation') == 1)
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Seller Login page Background') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="seller_login_page_bg">
                                        <input type="hidden" name="seller_login_page_bg"
                                            value="{{ get_setting('seller_login_page_bg') }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div>
                        @endif
                        @if (addon_is_activated('delivery_boy'))
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Delivery Boy Login page Background') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="delivery_boy_login_page_bg">
                                        <input type="hidden" name="delivery_boy_login_page_bg"
                                            value="{{ get_setting('delivery_boy_login_page_bg') }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Flash Deal Banner large') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="flash_deal_banner">
                                    <input type="hidden" name="flash_deal_banner"
                                        value="{{ get_setting('flash_deal_banner') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Flash Deal Banner Small') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="flash_deal_banner_small">
                                    <input type="hidden" name="flash_deal_banner_small"
                                        value="{{ get_setting('flash_deal_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Shop Banner large') }} <br> (2000 x 550)
                            </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="shop_banner">
                                    <input type="hidden" name="shop_banner" value="{{ get_setting('shop_banner') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Shop Banner Small') }} <br> ( 1000 x 700)
                            </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="shop_banner_small">
                                    <input type="hidden" name="shop_banner_small"
                                        value="{{ get_setting('shop_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Checkout Banner large') }} <br> (2000 x
                                550) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="checkout_banner">
                                    <input type="hidden" name="checkout_banner"
                                        value="{{ get_setting('checkout_banner') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Checkout Banner Small') }} <br> ( 1000 x
                                700) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="checkout_banner_small">
                                    <input type="hidden" name="checkout_banner_small"
                                        value="{{ get_setting('checkout_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Login Banner large') }} <br> (2000 x 550)
                            </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="login_banner">
                                    <input type="hidden" name="login_banner" value="{{ get_setting('login_banner') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Login Banner Small') }} <br> ( 1000 x
                                700) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="login_banner_small">
                                    <input type="hidden" name="login_banner_small"
                                        value="{{ get_setting('login_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Register Banner large') }} <br> (2000 x
                                550) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="register_banner">
                                    <input type="hidden" name="register_banner"
                                        value="{{ get_setting('register_banner') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Register Banner Small') }} <br> ( 1000 x
                                700) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="register_banner_small">
                                    <input type="hidden" name="register_banner_small"
                                        value="{{ get_setting('register_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Order Placed Banner large') }} <br> (2000
                                x 550) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="order_confirmed_banner">
                                    <input type="hidden" name="order_confirmed_banner"
                                        value="{{ get_setting('order_confirmed_banner') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Order Placed Banner Small') }} <br> (
                                1000 x 700) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="order_confirmed_banner_small">
                                    <input type="hidden" name="order_confirmed_banner_small"
                                        value="{{ get_setting('order_confirmed_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Cart Banner large') }} <br> (2000 x 550)
                            </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="cart_banner">
                                    <input type="hidden" name="cart_banner" value="{{ get_setting('cart_banner') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Cart Banner Small') }} <br> ( 1000 x
                                700) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="cart_banner_small">
                                    <input type="hidden" name="cart_banner_small"
                                        value="{{ get_setting('cart_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('checkout Banner large') }} <br> (2000 x
                                550) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="checkout_banner">
                                    <input type="hidden" name="checkout_banner"
                                        value="{{ get_setting('checkout_banner') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('checkout Banner Small') }} <br> ( 1000 x
                                700) </label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="checkout_banner_small">
                                    <input type="hidden" name="checkout_banner_small"
                                        value="{{ get_setting('checkout_banner_small') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Top Banner Content') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Top Banner Content') }}</label>
                            <div class="top_banner-target">
                                <input type="hidden" name="types[]" value="top_banner_content">
                                @if (get_setting('top_banner_content') != null)
                                    @foreach (json_decode(get_setting('top_banner_content'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col-md">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="top_banner_content">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Top Banner Content') }}"
                                                        name="top_banner_content[]"
                                                        value="{{ json_decode(get_setting('top_banner_content'), true)[$key] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='
                                <div class="row gutters-5">
                                            <div class="col-md">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="top_banner_content">
                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="top_banner_content[]" >
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                '
                                data-target=".top_banner-target">
                                {{ translate('Add New') }}
                            </button>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Global SEO') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="meta_title">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}"
                                    name="meta_title" value="{{ get_setting('meta_title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta description') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="meta_description">
                                <textarea class="resize-off form-control" placeholder="{{ translate('Description') }}" name="meta_description">{{ get_setting('meta_description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Keywords') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="meta_keywords">
                                <textarea class="resize-off form-control" placeholder="{{ translate('Keyword, Keyword') }}" name="meta_keywords">{{ get_setting('meta_keywords') }}</textarea>
                                <small class="text-muted">{{ translate('Separate with coma') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="meta_image">
                                    <input type="hidden" name="meta_image" value="{{ get_setting('meta_image') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Website Mail') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Email image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="invoice_image">
                                    <input type="hidden" name="invoice_image"
                                        value="{{ get_setting('invoice_image') }}" class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Register Mail content') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="register_mail_content">
                                <textarea class="aiz-text-editor" name="register_mail_content">{{ get_setting('register_mail_content') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Cart Mail content') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="cart_mail_content">
                                <textarea class="aiz-text-editor" name="cart_mail_content">{{ get_setting('cart_mail_content') }}</textarea>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Cookies Agreement') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Cookies Agreement Text') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="cookies_agreement_text">
                                <textarea name="cookies_agreement_text" rows="4" class="aiz-text-editor form-control"
                                    data-buttons='[["font", ["bold"]],["insert", ["link"]]]'>{{ get_setting('cookies_agreement_text') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Show Cookies Agreement?') }}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_cookies_agreement">
                                    <input type="checkbox" name="show_cookies_agreement"
                                        @if (get_setting('show_cookies_agreement') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Website Popup') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Show website popup?') }}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_website_popup">
                                    <input type="checkbox" name="show_website_popup"
                                        @if (get_setting('show_website_popup') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Popup image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="popup_image">
                                    <input type="hidden" name="popup_image" value="{{ get_setting('popup_image') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Popup content') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="website_popup_content">
                                <textarea class="aiz-text-editor" name="website_popup_content">{{ get_setting('website_popup_content') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Show Subscriber form?') }}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_subscribe_form">
                                    <input type="checkbox" name="show_subscribe_form"
                                        @if (get_setting('show_subscribe_form') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Custom Script') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-from-label">{{ translate('Header custom script - before </head>') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="header_script">
                                <textarea name="header_script" rows="4" class="form-control" placeholder="<script>
                                    & #10;...&# 10;
                                </script>">{{ get_setting('header_script') }}</textarea>
                                <small>{{ translate('Write script with <script> tag') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-from-label">{{ translate('Footer custom script - before </body>') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="footer_script">
                                <textarea name="footer_script" rows="4" class="form-control" placeholder="<script>
                                    & #10;...&# 10;
                                </script>">{{ get_setting('footer_script') }}</textarea>
                                <small>{{ translate('Write script with <script> tag') }}</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Best Selling Abayat Page') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Image') }}</label>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Abayat Category Image') }} <small>( w 1300 x h 708 )</small></label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="abayat_category_image">
                                        <input type="hidden" name="abayat_category_image"
                                            value="{{ get_setting('abayat_category_image') }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('description_to_store') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_description_to_store">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_description_to_store') }}"
                                        name="abayat_description_to_store">{{ get_setting('abayat_description_to_store') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('the Quality') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_category_quality">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_category_quality') }}"
                                        name="abayat_category_quality">{{ get_setting('abayat_category_quality') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Category Long Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_category__slug">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_category__slug') }}"
                                        name="abayat_category__slug">{{ get_setting('abayat_category__slug') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('abayat_category Discussion') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_category_discussion">
                                    <textarea class="aiz-text-editor    " placeholder="{{ translate('abayat_category_discussion') }}"
                                        name="abayat_category_discussion">{{ get_setting('abayat_category_discussion') }}</textarea>

                                </div>
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Abayat Shal Page') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Image') }}</label>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Shal Category Image') }}  <small>( w 1220 x h 609 )</small> </label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="abayat_shal_image">
                                        <input type="hidden" name="abayat_shal_image"
                                            value="{{ get_setting('abayat_shal_image') }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('description_to_store') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="ab_sh_description_to_store">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('ab_sh_description_to_store') }}"
                                        name="ab_sh_description_to_store">{{ get_setting('ab_sh_description_to_store') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('the Quality') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_shal_quality">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_shal_quality') }}" name="abayat_shal_quality">{{ get_setting('abayat_shal_quality') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Category Long Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_shal__slug">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_shal__slug') }}" name="abayat_shal__slug">{{ get_setting('abayat_shal__slug') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('abayat_shal Discussion') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_shal_discussion">
                                    <textarea class="aiz-text-editor    " placeholder="{{ translate('abayat_shal_discussion') }}"
                                        name="abayat_shal_discussion">{{ get_setting('abayat_shal_discussion') }}</textarea>

                                </div>
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('Abayat 150 or less Page') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Image') }}</label>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat 150 or less Category Image') }} <small>( w 1220 x h 609 )</small></label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="abayat_150_or_less_image">
                                        <input type="hidden" name="abayat_150_or_less_image"
                                            value="{{ get_setting('abayat_150_or_less_image') }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Products Section Description') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="products_section_description">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('products_section_description') }}"
                                        name="products_section_description">{{ get_setting('products_section_description') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('ab_less_description_to_store') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="ab_less_description_to_store">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('ab_less_description_to_store') }}"
                                        name="ab_less_description_to_store">{{ get_setting('ab_less_description_to_store') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('the Quality') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_150_or_less_quality">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_150_or_less_quality') }}"
                                        name="abayat_150_or_less_quality">{{ get_setting('abayat_150_or_less_quality') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Category Long Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_150_or_less__slug">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('abayat_150_or_less__slug') }}"
                                        name="abayat_150_or_less__slug">{{ get_setting('abayat_150_or_less__slug') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('abayat_150_or_less Discussion') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="abayat_150_or_less_discussion">
                                    <textarea class="aiz-text-editor    " placeholder="{{ translate('abayat_150_or_less_discussion') }}"
                                        name="abayat_150_or_less_discussion">{{ get_setting('abayat_150_or_less_discussion') }}</textarea>

                                </div>
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('offers Page') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>{{ translate('Image') }}</label>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('offers Image') }} <small>( w 1300 x h 708 )</small></label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="offers_image">
                                        <input type="hidden" name="offers_image"
                                            value="{{ get_setting('offers_image') }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('offers_description_to_store') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="offers_description_to_store">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('offers_description_to_store') }}"
                                        name="offers_description_to_store">{{ get_setting('offers_description_to_store') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('offers Long Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}
                                            </div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="offers_long_image">
                                        <input type="hidden" name="offers_long_image"
                                            value="{{ get_setting('offers_long_image') }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Category Long Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="offers__slug">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('offers__slug') }}" name="offers__slug">{{ get_setting('offers__slug') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('offers Discussion') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="offers_discussion">
                                    <textarea class="aiz-text-editor    " placeholder="{{ translate('offers_discussion') }}"
                                        name="offers_discussion">{{ get_setting('offers_discussion') }}</textarea>

                                </div>
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
             <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('klosh Page') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Category Long Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="klosh__slug">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('klosh__slug') }}" name="klosh__slug">{{ get_setting('klosh__slug') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('klosh Discussion') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="klosh_discussion">
                                    <textarea class="aiz-text-editor    " placeholder="{{ translate('klosh_discussion') }}"
                                        name="klosh_discussion">{{ get_setting('klosh_discussion') }}</textarea>

                                </div>
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
             <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ translate('summer Page') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group"> 
                            <div class="form-group row">
                                <label
                                    class="col-md-3 col-from-label">{{ translate('Abayat Category Long Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="summer__slug">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('summer__slug') }}" name="summer__slug">{{ get_setting('summer__slug') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('summer Discussion') }}</label>
                                <div class="col-md-8">
                                    <input type="hidden" name="types[]" value="summer_discussion">
                                    <textarea class="aiz-text-editor" placeholder="{{ translate('summer_discussion') }}"
                                        name="summer_discussion">{{ get_setting('summer_discussion') }}</textarea>

                                </div>
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h6 class="mb-0">{{ translate('Common questions & answers') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="common-target">
                                <input type="hidden" name="types[]" value="common_questions">
                                <input type="hidden" name="types[]" value="common_answer">
                                @if (get_setting('common_questions') != null)
                                    @foreach (json_decode(get_setting('common_questions'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="common_questions">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Question') }}"
                                                        name="common_questions[]"
                                                        value="{{ json_decode(get_setting('common_questions'), true)[$key] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="common_answer">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('answer') }}" name="common_answer[]"
                                                        value="{{ json_decode(get_setting('common_answer'), true)[$key] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='
                                <div class="row gutters-5">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="common_questions">
                                                    <input type="text" class="form-control" placeholder="{{ translate('Question') }}"
                                                        name="common_questions[]">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="common_answer">
                                                    <input type="text" class="form-control" placeholder="{{ translate('answer') }}"
                                                        name="common_answer[]">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                '
                                data-target=".common-target">
                                {{ translate('Add New') }}
                            </button>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
