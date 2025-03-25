    <button type="button" class="btn btn-icon p-0 d-lg-none d-block " data-toggle="class-toggle"
                            data-target=".aiz-filter-sidebar">
                            <i class="la la-filter la-2x"></i>
                         </button> 
                    @if (count($products) > 0)
                        <!-- Products -->
                        <div
                            class="row gutters-16 row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-2  ">
                            @foreach ($products as $key => $product)
                                <?php $uniqueID = uniqid('prefix_' . $product->id . '_'); ?>
                                <div class="col" style="margin-bottom: 30px !important">
                                    @include('frontend.partials.product_box_1', [
                                        'product' => $product,
                                        'uniqueID' => $uniqueID,
                                    ])
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-start align-items*center">
                                <div class="aiz-pagination">
                                    {{ $products->appends(request()->input())->links() }}
                                </div>
                            </div>
                            <div class="col-6 d-none d-lg-block shop-result"> </div>
                            
                        </div>
                    @endif