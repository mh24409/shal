@if (get_setting('home_categories') != null)
    <div style="transform: translateY(-140px);" class="aiz-carousel categories-aiz-carousel arrow-none" data-items="5" data-autoplay="true" data-xl-items="5" data-lg-items="5"
        data-md-items="2"data-sm-items="1" data-xs-items="1" data-arrows='true' data-dots='true' data-infinite='true'>
        @foreach (json_decode(get_setting('home_categories'), true) as $key => $value)
            @php
                $category = \App\Models\Category::find($value);
                $nameWords = explode(' ', $category->getTranslation('name'), 2);
            @endphp
             <div class=" category-container d-flex justify-content-center align-items-center">
                <div class="category-card position-relative ">
                    <img class="category-image-aiz" src="{{ uploaded_asset($category->cover_image) }}" alt="">
                    <div class="data absolute-bottom-left ">
                        <div class="p-2">
                            <div class="name h5 fs-30 fw-700 mb-0 text-capitalize mb-2 "> 
                                @if (count($nameWords) == 2)
                                    <span style="font-weight: normal;">{{ $nameWords[0] }}</span>
                                    <span style="font-weight: bolder;">{{ $nameWords[1] }}</span>
                                @else
                                    {{ $category->getTranslation('name') }}
                                @endif
                            </div>
                            <div class="desc mb-2">
                                {!! $category->getTranslation('description') !!}
                            </div>
                            <div class="button mb-2">
                                <a style="padding: 3px;" href="{{ route('products.category', $category->slug) }}"
                                    class="btn btn-md category-shopping-btn w-130px fs-19 px-3">
                                    {{ translate('Shopping Now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         @endforeach
    </div> 
@endif
