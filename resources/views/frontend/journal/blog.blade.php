@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->blog_page_title : __('Tips & Cerita Wisata') }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex flex-col lg:flex-row gap-16">
        
        {{-- Main Blog Feed --}}
        <div class="flex-grow">
            <div class="mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
                    {{ !empty($pageHeading) ? $pageHeading->blog_page_title : __('Tips & Cerita Wisata') }}
                </h1>
                <p class="text-lg text-gray-500 font-light">
                    {{ __('Temukan panduan memancing, cerita perjalanan, dan tips terbaik dari Go Fishi.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                @forelse ($blogs as $blog)
                    <article class="group relative flex flex-col bg-white rounded-3xl overflow-hidden border border-gray-100 hover:border-gray-200 hover:shadow-2xl transition-all duration-500">
                        <div class="aspect-[16/10] overflow-hidden bg-gray-100 relative">
                            <img src="{{ asset('assets/img/blogs/' . $blog->image) }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700" 
                                 alt="{{ $blog->title }}">
                            <div class="absolute top-4 left-4">
                                <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-xs font-bold text-gray-900 shadow-sm">
                                    {{ $blog->categoryName }}
                                </span>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-grow">
                            <div class="flex items-center text-xs text-rose-500 font-bold uppercase tracking-widest mb-4">
                                <i data-lucide="calendar" class="w-3 h-3 mr-2"></i>
                                {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-airbnb-red transition line-clamp-2 leading-tight">
                                <a href="{{ route('frontend.blog_details', ['slug' => $blog->slug, 'id' => $blog->id]) }}">
                                    {{ $blog->title }}
                                </a>
                            </h2>
                            <p class="text-gray-500 text-sm font-light leading-relaxed line-clamp-3 mb-6 flex-grow">
                                {{ strip_tags($blog->content) }}
                            </p>
                            <div class="pt-6 border-t border-gray-50 flex items-center justify-between mt-auto">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mr-3">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $blog->author ?? 'Admin' }}</span>
                                </div>
                                <a href="{{ route('frontend.blog_details', ['slug' => $blog->slug, 'id' => $blog->id]) }}" 
                                   class="text-airbnb-red font-bold text-sm flex items-center group-hover:translate-x-1 transition-transform">
                                    {{ __('Read More') }}
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                        <i data-lucide="book-open" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                        <h6 class="text-gray-500 font-medium">{{ __('Belum ada artikel yang dipublikasikan.') }}</h6>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-16 flex justify-center">
                {{ $blogs->links('pagination::tailwind') }}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="w-full lg:w-80 shrink-0 space-y-12">
            
            {{-- Search Widget --}}
            <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Cari Postingan') }}</h3>
                <form action="{{ route('frontend.blogs') }}" method="GET" class="relative">
                    <input type="text" name="title" value="{{ request('title') }}"
                           class="w-full pl-6 pr-12 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-airbnb-red/20 focus:border-airbnb-red outline-none transition text-sm" 
                           placeholder="{{ __('Search blogs...') }}">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-airbnb-red text-white rounded-xl shadow-md flex items-center justify-center hover:scale-105 transition">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            {{-- Categories Widget --}}
            <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Kategori') }}</h3>
                <div class="space-y-3">
                    @foreach ($categories as $category)
                        <a href="{{ route('frontend.blogs', ['category' => $category->slug]) }}" 
                           class="flex items-center justify-between p-3 rounded-xl transition {{ request('category') == $category->slug ? 'bg-rose-50 text-airbnb-red' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="text-sm font-semibold tracking-tight">{{ $category->name }}</span>
                            <span class="text-xs px-2.5 py-1 bg-gray-100 rounded-full font-bold group-hover:bg-rose-100 transition">
                                {{ $category->blogCount }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            @if (!empty(showAd(1)))
                <div class="flex justify-center">
                    {!! showAd(1) !!}
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
