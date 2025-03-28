@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Website Footer') }}</h1>
            </div>
        </div>
    </div>

    <!-- Language -->
    <ul class="nav nav-tabs nav-fill border-light">
        @foreach (\App\Models\Language::all() as $key => $language)
            <li class="nav-item">
                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                    href="{{ route('website.footer', ['lang' => $language->code]) }}">
                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                        class="mr-1">
                    <span>{{ $language->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Footer Widget -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">{{ translate('Footer Widget') }}</h6>
        </div>
        <div class="card-body">
            <div class="row gutters-10">
                <!-- About Widget -->
                <div class="col-lg-6">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('About Widget') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Footer Logo -->
                                <div class="form-group">
                                    <label class="form-label" for="signinSrEmail">{{ translate('Footer Logo') }}</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="footer_logo">
                                        <input type="hidden" name="footer_logo" class="selected-files"
                                            value="{{ get_setting('footer_logo') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="mobileApp">{{ translate('Mobile App Image') }}</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="types[]" value="mobile_app_image">
                                        <input type="hidden" name="mobile_app_image" class="selected-files"
                                            value="{{ get_setting('mobile_app_image') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>
                                <!-- About description -->
                                <div class="form-group">
                                    <label>{{ translate('About description') }} ({{ translate('Translatable') }})</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="about_us_description">
                                    <textarea class="aiz-text-editor" name="about_us_description"
                                        placeholder="Type.." data-min-height="150">
                                        {!! get_setting('about_us_description', null, $lang) !!}
                                    </textarea>
                                </div>
                                <!-- Play Store Link -->
                                <div class="form-group">
                                    <label>{{ translate('Play Store Link') }}</label>
                                    <input type="hidden" name="types[]" value="play_store_link">
                                    <input type="text" class="form-control" placeholder="http://" name="play_store_link"
                                        value="{{ get_setting('play_store_link') }}">
                                </div>
                                <!-- App Store Link -->
                                <div class="form-group">
                                    <label>{{ translate('App Store Link') }}</label>
                                    <input type="hidden" name="types[]" value="app_store_link">
                                    <input type="text" class="form-control" placeholder="http://" name="app_store_link"
                                        value="{{ get_setting('app_store_link') }}">
                                </div>
                                <!-- Update Button -->
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Widget -->
                <div class="col-lg-6">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Contact Info Widget') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Contact address -->
                                <div class="form-group">
                                    <label>{{ translate('Contact address') }} ({{ translate('Translatable') }})</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="contact_address">
                                    <input type="text" class="form-control" placeholder="{{ translate('Address') }}"
                                        name="contact_address" value="{{ get_setting('contact_address', null, $lang) }}">
                                </div>
                                <!-- Contact phone -->
                                <div class="form-group">
                                    <label>{{ translate('Contact phone') }}</label>
                                    <input type="hidden" name="types[]" value="contact_phone">
                                    <input type="text" class="form-control" placeholder="{{ translate('Phone') }}"
                                        name="contact_phone" value="{{ get_setting('contact_phone') }}">
                                </div>
                                <!-- Contact email -->
                                <div class="form-group">
                                    <label>{{ translate('Contact email') }}</label>
                                    <input type="hidden" name="types[]" value="contact_email">
                                    <input type="text" class="form-control" placeholder="{{ translate('Email') }}"
                                        name="contact_email" value="{{ get_setting('contact_email') }}">
                                </div>
                                <!-- Update Button -->
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Link Widget One -->
                <div class="col-lg-12">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Link Widget One') }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Title -->
                                <div class="form-group">
                                    <label>{{ translate('Title') }} ({{ translate('Translatable') }})</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_one">
                                    <input type="text" class="form-control" placeholder="Widget title"
                                        name="widget_one" value="{{ get_setting('widget_one', null, $lang) }}">
                                </div>
                                <!-- Links -->
                                <div class="form-group">
                                    <label>{{ translate('Links') }} - ({{ translate('Translatable') }}
                                        {{ translate('Label') }})</label>
                                    <div class="w3-links-target">
                                        <input type="hidden" name="types[][{{ $lang }}]"
                                            value="widget_one_labels">
                                        <input type="hidden" name="types[]" value="widget_one_links">
                                        @if (get_setting('widget_one_labels', null, $lang) != null)
                                            @foreach (json_decode(get_setting('widget_one_labels', null, $lang), true) as $key => $value)
                                                <div class="row gutters-5">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="{{ translate('Label') }}"
                                                                name="widget_one_labels[]" value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="http://" name="widget_one_links[]"
                                                                value="{{ json_decode(get_setting('widget_one_links'), true)[$key] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                            data-toggle="remove-parent" data-parent=".row">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="{{ translate('Label') }}" name="widget_one_labels[]">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_one_links[]">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>'
                                        data-target=".w3-links-target">
                                        {{ translate('Add New') }}
                                    </button>
                                </div>
                                <!-- Update Button -->
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">{{ translate('Footer Bottom') }}</h6>
        </div>
        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <!-- Copyright Widget -->
                <div class="card shadow-none bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">{{ translate('Copyright Widget ') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Copyright Text') }} ({{ translate('Translatable') }})</label>
                            <input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
                            <textarea class="aiz-text-editor form-control" name="frontend_copyright_text"
                                data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]'
                                placeholder="Type.." data-min-height="150">
                                {!! get_setting('frontend_copyright_text', null, $lang) !!}
                            </textarea>
                        </div>
                    </div>
                </div>

                <!-- Social Link Widget -->
                <div class="card shadow-none bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">{{ translate('Social Link Widget ') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-from-label">{{ translate('Show Social Links?') }}</label>
                            <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_social_links">
                                    <input type="checkbox" name="show_social_links"
                                        @if (get_setting('show_social_links') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Social Links') }}</label>
                            <!-- snapchat Link -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-snapchat"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="snapchat_link">
                                <input type="text" class="form-control" placeholder="http://" name="snapchat_link"
                                    value="{{ get_setting('snapchat_link') }}">
                            </div>
                            <!-- tiktok Link -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-tiktok"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="tiktok_link">
                                <input type="text" class="form-control" placeholder="http://" name="tiktok_link"
                                    value="{{ get_setting('tiktok_link') }}">
                            </div>
                            <!-- Twitter Link -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-twitter"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="twitter_link">
                                <input type="text" class="form-control" placeholder="http://" name="twitter_link"
                                    value="{{ get_setting('twitter_link') }}">
                            </div>
                            <!-- Instagram Link -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-instagram"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="instagram_link">
                                <input type="text" class="form-control" placeholder="http://" name="instagram_link"
                                    value="{{ get_setting('instagram_link') }}">
                            </div>
                            <!-- Youtube Link -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-youtube"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="youtube_link">
                                <input type="text" class="form-control" placeholder="http://" name="youtube_link"
                                    value="{{ get_setting('youtube_link') }}">
                            </div>
                            <!-- Linkedin Link -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-linkedin-in"></i></span>
                                </div>
                                <input type="hidden" name="types[]" value="linkedin_link">
                                <input type="text" class="form-control" placeholder="http://" name="linkedin_link"
                                    value="{{ get_setting('linkedin_link') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download App Link -->
                @if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Download App Link') }}</h6>
                        </div>
                        <div class="card-body">
                            <!-- Seller App Link -->
                            @if (get_setting('vendor_system_activation') == 1)
                                <div class="form-group">
                                    <label>{{ translate('Seller App Link') }}</label>
                                    <div class="input-group form-group">
                                        <input type="hidden" name="types[]" value="seller_app_link">
                                        <input type="text" class="form-control" placeholder="http://"
                                            name="seller_app_link" value="{{ get_setting('seller_app_link') }}">
                                    </div>
                                </div>
                            @endif
                            <!-- Delivery Boy App Link -->
                            @if (addon_is_activated('delivery_boy'))
                                <div class="form-group">
                                    <label>{{ translate('Delivery Boy App Link') }}</label>
                                    <div class="input-group form-group">
                                        <input type="hidden" name="types[]" value="delivery_boy_app_link">
                                        <input type="text" class="form-control" placeholder="http://"
                                            name="delivery_boy_app_link"
                                            value="{{ get_setting('delivery_boy_app_link') }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Payment Methods Widget -->
                <div class="card shadow-none bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">{{ translate('Payment Methods Widget ') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Payment Methods') }}</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="types[]" value="payment_method_images">
                                <input type="hidden" name="payment_method_images" class="selected-files"
                                    value="{{ get_setting('payment_method_images') }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- tips -->
                <div class="card shadow-none bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">{{ translate('questions & answers') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="tips-target">
                                <input type="hidden" name="types[]" value="tips_questions">
                                <input type="hidden" name="types[]" value="tips_answers">
                                @if (get_setting('tips_questions') != null)
                                    @foreach (json_decode(get_setting('tips_questions'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="tips_questions">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('Question') }}" name="tips_questions[]"
                                                        value="{{ json_decode(get_setting('tips_questions'), true)[$key] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="tips_answers">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ translate('answer') }}" name="tips_answers[]"
                                                        value="{{ json_decode(get_setting('tips_answers'), true)[$key] }}">
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
                                                        <input type="hidden" name="types[]" value="tips_questions">
                                                        <input type="text" class="form-control" placeholder="{{ translate('Question') }}"
                                                            name="tips_questions[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="tips_answers">
                                                        <input type="text" class="form-control" placeholder="{{ translate('answer') }}"
                                                            name="tips_answers[]">
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
                                data-target=".tips-target">
                                {{ translate('Add New') }}
                            </button>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ translate('Testimonials') }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('business_settings.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>{{ translate('Banner & Links') }}</label>
                                <div class="testimonials-target">
                                    <input type="hidden" name="types[]" value="testimonials_images">
                                    <input type="hidden" name="types[]" value="testimonials_name">
                                    <input type="hidden" name="types[]" value="testimonials_text">
                                    <input type="hidden" name="types[]" value="testimonials_rate">
                                    @if (get_setting('testimonials_images') != null)
                                        @foreach (json_decode(get_setting('testimonials_images'), true) as $key => $value)
                                            <div class="row gutters-5">
                                                <div class="col-md-5">
                                                    
                                                    <div class="form-group">
                                                        <div class="input-group" data-toggle="aizuploader"
                                                            data-type="image">
                                                            <div class="input-group-prepend">
                                                                <div
                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                    {{ translate('Browse') }} ( w 512 x h 512 )</div>
                                                            </div>
                                                            <div class="form-control file-amount">
                                                                {{ translate('Choose File') }}</div>
                                                            <input type="hidden" name="types[]"
                                                                value="testimonials_images">
                                                            <input type="hidden" name="testimonials_images[]"
                                                                class="selected-files"
                                                                value="{{ json_decode(get_setting('testimonials_images'), true)[$key] }}">
                                                        </div>
                                                        <div class="file-preview box sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="testimonials_name">
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ translate('comment user name') }}"
                                                            name="testimonials_name[]"
                                                            value="{{ json_decode(get_setting('testimonials_name'), true)[$key] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="testimonials_text">
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ translate('comment text') }}"
                                                            name="testimonials_text[]"
                                                            value="{{ json_decode(get_setting('testimonials_text'), true)[$key] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="testimonials_rate">
                                                        <input type="number" max="5" class="form-control"
                                                            placeholder="{{ translate('rate') }}"
                                                            name="testimonials_rate[]"
                                                            value="{{ json_decode(get_setting('testimonials_rate'), true)[$key] }}">
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
                                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                            <div class="input-group-prepend">
                                                                <div
                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                    {{ translate('Browse') }}</div>
                                                            </div>
                                                            <div class="form-control file-amount">
                                                                {{ translate('Choose File') }}</div>
                                                            <input type="hidden" name="types[]" value="testimonials_images">
                                                            <input type="hidden" name="testimonials_images[]"
                                                                class="selected-files">
                                                        </div>
                                                        <div class="file-preview box sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="testimonials_name">
                                                        <input type="text" class="form-control" placeholder="{{ translate('comment user name') }}"
                                                            name="testimonials_name[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="testimonials_text">
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ translate('comment text') }}"
                                                            name="testimonials_text[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="testimonials_rate">
                                                        <input type="number" max="5" class="form-control"
                                                            placeholder="{{ translate('rate') }}"
                                                            name="testimonials_rate[]">
                                                    </div>
                                                </div>
                                            </div>

                                '
                                    data-target=".testimonials-target">
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
                        <h6 class="mb-0">{{ translate('Slugs') }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('business_settings.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>{{ translate('Banner & Links') }}</label>
                                <div class="slugs-target">
                                    <input type="hidden" name="types[]" value="slugs_images">
                                    <input type="hidden" name="types[]" value="slugs_name">
                                    <input type="hidden" name="types[]" value="slugs_text">
                                    @if (get_setting('slugs_images') != null)
                                        @foreach (json_decode(get_setting('slugs_images'), true) as $key => $value)
                                            <div class="row gutters-5">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <div class="input-group" data-toggle="aizuploader"
                                                            data-type="image">
                                                            <div class="input-group-prepend">
                                                                <div
                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                    {{ translate('Browse') }}</div>
                                                            </div>
                                                            <div class="form-control file-amount">
                                                                {{ translate('Choose File') }}</div>
                                                            <input type="hidden" name="types[]" value="slugs_images">
                                                            <input type="hidden" name="slugs_images[]"
                                                                class="selected-files"
                                                                value="{{ json_decode(get_setting('slugs_images'), true)[$key] }}">
                                                        </div>
                                                        <div class="file-preview box sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="slugs_name">
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ translate('comment user name') }}"
                                                            name="slugs_name[]"
                                                            value="{{ json_decode(get_setting('slugs_name'), true)[$key] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="slugs_text">
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ translate('comment text') }}"
                                                            name="slugs_text[]"
                                                            value="{{ json_decode(get_setting('slugs_text'), true)[$key] }}">
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
                                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                            <div class="input-group-prepend">
                                                                <div
                                                                    class="input-group-text bg-soft-secondary font-weight-medium">
                                                                    {{ translate('Browse') }}</div>
                                                            </div>
                                                            <div class="form-control file-amount">
                                                                {{ translate('Choose File') }}</div>
                                                            <input type="hidden" name="types[]" value="slugs_images">
                                                            <input type="hidden" name="slugs_images[]"
                                                                class="selected-files">
                                                        </div>
                                                        <div class="file-preview box sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="slugs_name">
                                                        <input type="text" class="form-control" placeholder="{{ translate('comment user name') }}"
                                                            name="slugs_name[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="slugs_text">
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ translate('comment text') }}"
                                                            name="slugs_text[]">
                                                    </div>
                                                </div>
                                            </div>

                                '
                                    data-target=".slugs-target">
                                    {{ translate('Add New') }}
                                </button>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Update Button -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
