@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-6 py-16">
    <div class="mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">{{ $title }}</h1>
        <div class="h-1.5 w-20 bg-airbnb-red rounded-full"></div>
    </div>

    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed airbnb-content">
        {!! $pageInfo->content !!}
    </div>

    @if (!empty(showAd(3)))
        <div class="mt-16 text-center">
            {!! showAd(3) !!}
        </div>
    @endif
</div>

<style>
    .airbnb-content h2 { @apply text-2xl font-bold text-gray-900 mt-10 mb-4; }
    .airbnb-content p { @apply mb-6; }
    .airbnb-content ul { @apply list-disc list-inside mb-6 space-y-2 pl-4; }
    .airbnb-content strong { @apply text-gray-900; }
</style>
@endsection
