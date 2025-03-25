@extends('frontend.layouts.app')
@section('content')
    @include('frontend.home_page.slider')
    @include('frontend.home_page.categories')
    <div style="margin-top: -120px;">
        @include('frontend.home_page.banner1')
    </div>
    @include('frontend.home_page.section_one')
    @include('frontend.home_page.banner2')
    @include('frontend.home_page.section_two')
    @include('frontend.home_page.section_three')
    @include('frontend.home_page.section_four')
@endsection
@section('script')
    @if ($login == 1)
        <script>
            $('#loginModal').modal('show');
        </script>
    @endif
@endsection