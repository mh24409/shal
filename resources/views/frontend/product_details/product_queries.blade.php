@if (get_setting('product_query_activation') == 0)
    <div class="bg-white   mt-4 mb-4" id="product_query">
        {{-- <div class="p-3 p-sm-4">
            <h3 class="fs-16 fw-700 mb-0">
                <span>{{ translate(' Product Queries ') }} ({{ $total_query }})</span>
            </h3>
        </div> --}} 
        @foreach ($detailedProduct->questions as $question)
            @if ($question)
                <div class="produc-queries mb-2 p-2">
                    <div class="query d-flex my-2">
                        <div class="ml-3">
                            <div class="fs-13 fw-700 text-dark">{{ $question->getTranslation('question') }}</div>
                        </div>
                    </div>
                    <div class="answer d-flex my-2">
                        <div class="ml-3">
                            <div class="fs-11 fw-300 text-dark">
                                {{ $question->getTranslation('answer') }}
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        @foreach (\App\Models\CustomQuestion::where('product_id',$detailedProduct->id)->get() as $custom_question)
            @if ($custom_question)
                <div class="produc-queries mb-2 p-2">
                    <div class="query d-flex my-2">
                        <div class="ml-3">
                            <div class="fs-13 fw-700 text-dark">{{ $custom_question->question }}</div>
                        </div>
                    </div>
                    <div class="answer d-flex my-2">
                        <div class="ml-3">
                            <div class="fs-11 fw-300 text-dark">
                                {{ $custom_question->answer }}
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
