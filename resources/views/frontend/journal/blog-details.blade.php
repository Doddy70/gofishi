@extends('frontend.layout-airbnb')

@php
  $title = strlen($details->title) > 40 ? mb_substr($details->title, 0, 40, 'UTF-8') . '...' : $details->title;
@endphp

@section('pageHeading')
  {{ $details->title }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col lg:flex-row gap-16">
        
        {{-- Main Article Content --}}
        <div class="flex-grow max-w-4xl">
            {{-- Article Header --}}
            <div class="mb-12">
                <div class="flex items-center gap-2 mb-6">
                    <span class="px-4 py-1.5 bg-rose-50 text-airbnb-red rounded-full text-xs font-bold uppercase tracking-widest border border-rose-100">
                        {{ $details->categoryName }}
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="text-sm font-medium text-gray-500">{{ \Carbon\Carbon::parse($details->created_at)->format('M d, Y') }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-8 leading-[1.15] tracking-tight">
                    {{ $details->title }}
                </h1>

            </div>

            {{-- Feature Image --}}
            <div class="aspect-[21/9] rounded-[2.5rem] overflow-hidden mb-12 shadow-2xl border-4 border-white">
                <img src="{{ asset('assets/img/blogs/' . $details->image) }}" class="w-full h-full object-cover" alt="{{ $details->title }}">
            </div>

            {{-- YouTube Video Integration --}}
            @if (!empty($details->video_url))
                @php
                  $videoUrl = $details->video_url;
                  if (preg_match("/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\"&?\/ ]{11})/", $videoUrl, $matches)) {
                      $videoId = $matches[1];
                  } else {
                      $videoId = null;
                  }
                @endphp
                @if ($videoId)
                    <div class="aspect-video rounded-3xl overflow-hidden mb-12 shadow-xl bg-black border-4 border-white">
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
            @endif

            {{-- Article Body --}}
            <div class="prose prose-lg max-w-none text-gray-600 font-light leading-relaxed blog-content-premium">
                {!! replaceBaseUrl($details->content, 'summernote') !!}
            </div>

            {{-- Share & Footer --}}
            <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest">{{ __('Bagikan Artikel') }}</h4>
                    <button class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 hover:bg-rose-50 hover:text-airbnb-red transition shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#socialMediaModal">
                        <i data-lucide="share-2" class="w-5 h-5"></i>
                    </button>
                </div>
                <a href="{{ route('frontend.blogs') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-airbnb-red transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    {{ __('Kembali ke Blog') }}
                </a>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="w-full lg:w-96 shrink-0 space-y-12">
            
            {{-- Recent Posts Widget --}}
            <div class="p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-8 border-l-4 border-airbnb-red pl-4">{{ __('Artikel Terbaru') }}</h3>
                <div class="space-y-8">
                    @foreach ($recent_blogs as $blog)
                        <article class="flex gap-4 group">
                            <div class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden bg-gray-100 shadow-inner">
                                <img src="{{ asset('assets/img/blogs/' . $blog->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $blog->title }}">
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($blog->created_at)->format('M d') }}</span>
                                <h4 class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug group-hover:text-airbnb-red transition">
                                    <a href="{{ route('frontend.blog_details', ['slug' => $blog->slug, 'id' => $blog->id]) }}">
                                        {{ $blog->title }}
                                    </a>
                                </h4>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- Categories Widget --}}
            <div class="p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-8 border-l-4 border-airbnb-red pl-4">{{ __('Categories') }}</h3>
                <div class="space-y-3">
                    @foreach ($categories as $category)
                        <a href="{{ route('frontend.blogs', ['category' => $category->slug]) }}" 
                           class="flex items-center justify-between p-3 rounded-xl transition {{ request('category') == $category->slug ? 'bg-rose-50 text-airbnb-red' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="text-sm font-semibold tracking-tight">{{ $category->name }}</span>
                            <span class="text-xs px-2.5 py-1 bg-gray-100 rounded-full font-bold">
                                {{ $category->blogCount }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            @if (!empty(showAd(1)))
                <div class="flex justify-center p-4">
                    {!! showAd(1) !!}
                </div>
            @endif
        </aside>
    </div>
</div>

@include('frontend.journal.share')
@endsection

@push('styles')
<style>
    .blog-content-premium p { margin-bottom: 2rem; }
    .blog-content-premium h2 { font-size: 2rem; font-weight: 800; color: #111827; margin: 3rem 0 1.5rem; }
    .blog-content-premium h3 { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 2.5rem 0 1.25rem; }
    .blog-content-premium img { border-radius: 2rem; margin: 3rem 0; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); }
</style>
@endpush
