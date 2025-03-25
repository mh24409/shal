<div class="sections-between-space"></div>

<section class="tips container mt-5 ">
    <div class="row">
        @if (get_setting('tips_questions') != null)
            @foreach (json_decode(get_setting('tips_questions'), true) as $key => $value)
                <div class="col-md-6 p-1 d-flex flex-column">
                    <div style="width: 80%;" class="question h5 fs-30 fw-700 mb-4 text-capitalize text-gray">
                        {{ json_decode(get_setting('tips_questions'), true)[$key] }}</div>
                    <div style="width: 80%;" class="answer h5 fs-20 fw-400 mb-0 text-capitalize text-gray">
                        {{ json_decode(get_setting('tips_answers'), true)[$key] }}</div>
                </div>
            @endforeach
        @endif
    </div>
</section>