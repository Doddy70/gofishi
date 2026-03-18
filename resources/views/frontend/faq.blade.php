@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->faq_page_title : __('FAQ') }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
            {{ !empty($pageHeading) ? $pageHeading->faq_page_title : __('Frequently Asked Questions') }}
        </h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto font-light">
            {{ __('Punya pertanyaan? Kami punya jawabannya. Berikut adalah hal-hal yang paling sering ditanyakan oleh komunitas kami.') }}
        </p>
    </div>

    <div class="space-y-4" x-data="{ active: null }">
        @if (count($faqs) == 0)
            <div class="py-12 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <i data-lucide="help-circle" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <h6 class="text-gray-500 font-medium">{{ __('Belum ada FAQ yang tersedia.') }}</h6>
            </div>
        @else
            @foreach ($faqs as $faq)
                <div class="border border-gray-100 rounded-2xl overflow-hidden hover:border-gray-200 transition bg-white shadow-sm">
                    <button @click="active = (active === {{ $loop->index }} ? null : {{ $loop->index }})" 
                            class="w-full px-8 py-6 text-left flex items-center justify-between group">
                        <span class="text-lg font-bold text-gray-800 group-hover:text-airbnb-red transition">{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" 
                           class="w-5 h-5 text-gray-400 transition-transform duration-300"
                           :class="active === {{ $loop->index }} ? 'rotate-180 text-airbnb-red' : ''"></i>
                    </button>
                    <div x-show="active === {{ $loop->index }}" 
                         x-cloak
                         x-collapse
                         class="px-8 pb-8 text-gray-600 leading-relaxed font-light">
                         {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if (!empty(showAd(3)))
        <div class="mt-20 flex justify-center">
            {!! showAd(3) !!}
        </div>
    @endif
</div>
@endsection
