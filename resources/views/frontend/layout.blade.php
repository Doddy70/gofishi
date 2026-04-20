@extends('frontend.layout-airbnb')

{{-- 
    This is a compatibility wrapper for older views that still extend 'frontend.layout'.
    It delegates everything to the new 'layout-airbnb'.
--}}

@section('content')
    @yield('content')
@endsection
