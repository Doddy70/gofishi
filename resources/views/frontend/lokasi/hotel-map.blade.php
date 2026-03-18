@extends('frontend.layout-airbnb')

@php
    $showCategories = false; // Kita sembunyikan kategori di halaman peta agar tidak terlalu ramai
@endphp

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->hotel_page_title }}
  @else
    {{ __('Lokasi') }}
  @endif
@endsection

@section('content')
  <div class="split-view-container" style="display: flex; flex-wrap: nowrap; width: 100%; align-items: flex-start;">
    
    <!-- List Panel (Left) -->
    <div class="list-panel overflow-auto" style="height: calc(100vh - 170px); flex: 0 0 55%; max-width: 55%; padding: 24px 24px 24px 5%;">
      
      <!-- Top Pill Filters (to be implemented) -->
      <div class="top-pill-filters mb-4 pb-3 border-bottom d-flex align-items-center gap-3 overflow-auto" style="white-space: nowrap;">
        <button class="btn btn-sm btn-outline-secondary rounded-pill" type="button" data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas" aria-controls="widgetOffcanvas">
          <i class="fal fa-sliders-h me-2"></i> {{ __('Filters') }}
        </button>
        <!-- Horizontal Scrollable filters will go here -->
      </div>

      <div class="listing-area hotels">
          <!-- Sort-Area -->
            <div class="col-6 ">
              <button type="button" class="btn btn-sm btn-primary radius-sm d-block d-lg-none float-end mb-20"
                data-bs-toggle="modal" data-bs-target="#mapModal">
                {{ __('View Map') }}
              </button>
            </div>
            <!-- Sort List -->
            <ul class="sort-list list-unstyled w-100 justify-content-between">
              <li class="item">

                <div class="form-group icon-end location-group overflow-hidden">
                  <input class="form-control" value="{{ request()->input('location') }}" type="text" autocomplete="off"
                    placeholder="{{ __('Enter location') }}" name="location" id="location">
                  @if ($basicInfo->google_map_api_key_status == 1)
                    <button type="button" class="btn btn-sm current-location" onclick="getCurrentLocation()">
                      <i class="fas fa-crosshairs"></i>
                    </button>
                  @endif
                </div>
              </li>
              <li class="item">
                <form>
                  <div class="sort-item d-flex align-items-center">
                    <label class="me-2 font-sm" for="select_sort">{{ __('Sort By') }}:</label>
                    <select name="select_sort" id="select_sort" class="sort nice-select right color-dark">
                      @if ($websiteInfo->google_map_api_key_status == 1 && request()->input('location'))
                        <option {{ request()->input('sort') == 'nearest' ? 'selected' : '' }} value="nearest">
                          {{ __('Location: Nearest First') }}</option>
                        <option {{ request()->input('sort') == 'farthest' ? 'selected' : '' }} value="farthest">
                          {{ __('Location: Farthest First') }}</option>
                      @endif
                      <option {{ request()->input('sort') == 'new' ? 'selected' : '' }} value="new">
                        {{ __('Newest on top') }}
                      </option>
                      <option {{ request()->input('sort') == 'old' ? 'selected' : '' }} value="old">
                        {{ __('Oldest on top') }}
                      </option>
                      <option {{ request()->input('sort') == 'starhigh' ? 'selected' : '' }} value="starhigh">
                        {{ __('Stars: High to Low') }}
                      </option>
                      <option {{ request()->input('sort') == 'starlow' ? 'selected' : '' }} value="starlow">
                        {{ __('Stars: Low to High') }}
                      </option>

                      <option {{ request()->input('sort') == 'reviewshigh' ? 'selected' : '' }} value="reviewshigh">
                        {{ __('Reviews: High to Low') }}
                      </option>
                      <option {{ request()->input('sort') == 'reviewslow' ? 'selected' : '' }} value="reviewslow">
                        {{ __('Reviews: Low to High') }}
                      </option>
                    </select>
                  </div>
                </form>
              </li>
            </ul>
          </div>

          <div class="search-container mb-40">

            @if (count($featured_contents) < 1 && count($currentPageData) < 1)

              <div class="p-3 text-center bg-light radius-md">
                <h6 class="mb-0">{{ __('NO LOKASI FOUND') }}</h6>
              </div>
            @else
              <div class="row pb-15" data-aos="fade-up">
                @foreach ($featured_contents as $hotel_content)
                  @php
                    $city = $hotel_content->city_id ? App\Models\Location\City::where('id', $hotel_content->city_id)->first()->name ?? '' : '';
                    $country = $hotel_content->country_id ? App\Models\Location\Country::where('id', $hotel_content->country_id)->first()->name ?? '' : '';
                    $checkWishList = Auth::guard('web')->check() ? checkHotelWishList($hotel_content->id, Auth::guard('web')->user()->id) : false;
                  @endphp
                  
                  <div class="col-12 col-sm-6 col-lg-4">
                    <div class="airbnb-card position-relative mb-4">
                      <!-- Badge Featured -->
                      <div class="position-absolute top-0 start-0 p-2 z-1">
                        <span class="badge bg-white text-dark shadow-sm">{{ __('Featured') }}</span>
                      </div>
                      
                      <!-- Image Carousel / Hero -->
                      <a href="{{ route('frontend.lokasi.details', ['slug' => $hotel_content->slug, 'id' => $hotel_content->id]) }}" class="d-block position-relative mb-2">
                        <div class="ratio ratio-1-1 overflow-hidden" style="border-radius: 12px;">
                          <img class="lazyload w-100 h-100" data-src="{{ asset('assets/img/hotel/logo/' . $hotel_content->logo) }}" alt="Lokasi" style="object-fit: cover;">
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="position-absolute top-0 end-0 p-2 z-2" onclick="event.preventDefault(); window.location.href='{{ $checkWishList == false ? route('frontend.perahu.add_wishlist', $hotel_content->id) : route('frontend.perahu.remove_wishlist', $hotel_content->id) }}'">
                           <div class="wishlist-btn d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; color: {{ $checkWishList ? '#e31c5f' : 'rgba(0,0,0,0.5)' }};">
                             <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="display: block; fill: {{ $checkWishList ? '#e31c5f' : 'rgba(0, 0, 0, 0.5)' }}; height: 24px; width: 24px; stroke: white; stroke-width: 2; overflow: visible;"><path d="m16 28c7-4.733 14-10 14-17 0-1.792-.683-3.583-2.05-4.95-1.367-1.366-3.158-2.05-4.95-2.05-1.791 0-3.583.684-4.949 2.05l-2.051 2.051-2.05-2.051c-1.367-1.366-3.158-2.05-4.95-2.05-1.791 0-3.583.684-4.949 2.05-1.367 1.367-2.051 3.158-2.051 4.95 0 7 7 12.267 14 17z"></path></svg>
                           </div>
                        </div>
                      </a>

                      <!-- Card Content -->
                      <div class="card-text">
                        <div class="d-flex justify-content-between align-items-start">
                          <h6 class="mb-0 fw-bold text-truncate" style="font-size: 15px;">{{ $city }}{{ $city && $country ? ', ' : '' }}{{ $country }}</h6>
                          <div class="d-flex align-items-center" style="font-size: 14px;">
                            <i class="fas fa-star me-1" style="font-size: 11px;"></i> 
                            <span>{{ number_format($hotel_content->average_rating, 2) }}</span>
                          </div>
                        </div>
                        <div class="text-muted text-truncate" style="font-size: 14px;">{{ $hotel_content->title }}</div>
                        <div class="text-muted" style="font-size: 14px;">
                           @if(!empty($hotel_content->distance))
                             {{ number_format($hotel_content->distance, 1) }} km &middot; 
                           @endif
                           {{ totalHotelRoom($hotel_content->id) }} {{ __('Perahu') }}
                        </div>
                        <div class="mt-1" style="font-size: 15px;">
                          <span class="fw-bold">{{ $hotel_content->categoryName }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach

                @foreach ($currentPageData as $hotel_content)
                  @php
                    $city = $hotel_content->city_id ? App\Models\Location\City::where('id', $hotel_content->city_id)->first()->name ?? '' : '';
                    $country = $hotel_content->country_id ? App\Models\Location\Country::where('id', $hotel_content->country_id)->first()->name ?? '' : '';
                    $checkWishList = Auth::guard('web')->check() ? checkHotelWishList($hotel_content->id, Auth::guard('web')->user()->id) : false;
                  @endphp
                  
                  <div class="col-12 col-sm-6 col-lg-4">
                    <div class="airbnb-card position-relative mb-4">
                      
                      <!-- Image Carousel / Hero -->
                      <a href="{{ route('frontend.lokasi.details', ['slug' => $hotel_content->slug, 'id' => $hotel_content->id]) }}" class="d-block position-relative mb-2">
                        <div class="ratio ratio-1-1 overflow-hidden" style="border-radius: 12px;">
                          <img class="lazyload w-100 h-100" data-src="{{ asset('assets/img/hotel/logo/' . $hotel_content->logo) }}" alt="Lokasi" style="object-fit: cover;">
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="position-absolute top-0 end-0 p-2 z-2" onclick="event.preventDefault(); window.location.href='{{ $checkWishList == false ? route('frontend.perahu.add_wishlist', $hotel_content->id) : route('frontend.perahu.remove_wishlist', $hotel_content->id) }}'">
                           <div class="wishlist-btn d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; color: {{ $checkWishList ? '#e31c5f' : 'rgba(0,0,0,0.5)' }};">
                             <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="display: block; fill: {{ $checkWishList ? '#e31c5f' : 'rgba(0, 0, 0, 0.5)' }}; height: 24px; width: 24px; stroke: white; stroke-width: 2; overflow: visible;"><path d="m16 28c7-4.733 14-10 14-17 0-1.792-.683-3.583-2.05-4.95-1.367-1.366-3.158-2.05-4.95-2.05-1.791 0-3.583.684-4.949 2.05l-2.051 2.051-2.05-2.051c-1.367-1.366-3.158-2.05-4.95-2.05-1.791 0-3.583.684-4.949 2.05-1.367 1.367-2.051 3.158-2.051 4.95 0 7 7 12.267 14 17z"></path></svg>
                           </div>
                        </div>
                      </a>

                      <!-- Card Content -->
                      <div class="card-text">
                        <div class="d-flex justify-content-between align-items-start">
                          <h6 class="mb-0 fw-bold text-truncate" style="font-size: 15px;">{{ $city }}{{ $city && $country ? ', ' : '' }}{{ $country }}</h6>
                          <div class="d-flex align-items-center" style="font-size: 14px;">
                            <i class="fas fa-star me-1" style="font-size: 11px;"></i> 
                            <span>{{ number_format($hotel_content->average_rating, 2) }}</span>
                          </div>
                        </div>
                        <div class="text-muted text-truncate" style="font-size: 14px;">{{ $hotel_content->title }}</div>
                        <div class="text-muted" style="font-size: 14px;">
                           @if(!empty($hotel_content->distance))
                             {{ number_format($hotel_content->distance, 1) }} km &middot; 
                           @endif
                           {{ totalHotelRoom($hotel_content->id) }} {{ __('Perahu') }}
                        </div>
                        <div class="mt-1" style="font-size: 15px;">
                          <span class="fw-bold">{{ $hotel_content->categoryName }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach

                @if($currentPageData->lastPage() > 1)
                <div class="airbnb-pagination" data-aos="fade-up">
                  @php 
                    $totalPages = $currentPageData->lastPage(); 
                    $currentPage = $currentPageData->currentPage();
                  @endphp
                  
                  @if($currentPage == 1)
                    <button class="page-btn disabled" disabled><i class="far fa-angle-left"></i></button>
                  @else
                    <a class="page-btn page-link" data-page="{{ $currentPage - 1 }}"><i class="far fa-angle-left"></i></a>
                  @endif

                  <div class="page-numbers">
                    @for($i = 1; $i <= $totalPages; $i++)
                      @if($i == $currentPage)
                        <button class="page-num active">{{ $i }}</button>
                      @elseif($i == 1 || $i == $totalPages || abs($i - $currentPage) <= 2)
                        <a class="page-num page-link" data-page="{{ $i }}">{{ $i }}</a>
                      @elseif(abs($i - $currentPage) == 3)
                        <span class="page-ellipsis">…</span>
                      @endif
                    @endfor
                  </div>

                  @if($currentPageData->hasMorePages())
                    <a class="page-btn page-link" data-page="{{ $currentPage + 1 }}"><i class="far fa-angle-right"></i></a>
                  @else
                    <button class="page-btn disabled" disabled><i class="far fa-angle-right"></i></button>
                  @endif
                </div>
                @endif
              </div>
            @endif
          </div>
          @if (!empty(showAd(3)))
            <div class="text-center">
              {!! showAd(3) !!}
            </div>
          @endif
      </div>
    
      <!-- Map Panel (Right) -->
      <div class="map-panel" style="flex: 0 0 45%; max-width: 45%; position: sticky; top: 100px; height: calc(100vh - 100px);">
        <div class="main-map h-100 w-100">
          <div id="main-map" style="width: 100%; height: 100%;"></div>
        </div>
      </div>
    </div>
  <!-- Split View Layout end -->
  <form action="{{ route('frontend.lokasi') }}" id="searchForm" method="GET">
    <input type="hidden" name="title" id="title"value="{{ request()->input('title') }}">
    <input type="hidden" name="category" id="category"value="{{ request()->input('category') }}">
    <input type="hidden" name="max_val" id="max_val"value="{{ request()->input('max_val') }}">
    <input type="hidden" name="min_val" id="min_val"value="{{ request()->input('min_val') }}">
    <input type="hidden" name="ratings" id="ratings"value="{{ request()->input('ratings') }}">
    <input type="hidden" name="amenitie" id="amenitie"value="{{ request()->input('amenitie') }}">
    <input type="hidden" name="sort" id="sort"value="{{ request()->input('sort') }}">
    <input type="hidden" name="vendor" id="vendor"value="{{ request()->input('vendor') }}">
    <input type="hidden" name="country" id="country"value="{{ request()->input('country') }}">
    <input type="hidden" name="state" id="state"value="{{ request()->input('state') }}">
    <input type="hidden" name="city" id="city"value="{{ request()->input('city') }}">
    <input type="hidden" name="checkInDates" id="checkInDates"value="{{ request()->input('checkInDates') }}">
    <input type="hidden" name="checkInTimes" id="checkInTimes"value="{{ request()->input('checkInTimes') }}">
    <input type="hidden" name="hour" id="hour"value="{{ request()->input('hour') }}">
    <input type="hidden" name="stars" id="stars"value="{{ request()->input('stars') }}">
    <input type="hidden" name="page" id="page"value="">
    <input type="hidden" id="location_val" name="location_val" value="{{ request()->input('location') }}">
  </form>
  <!-- Modal -->
  <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mapModalLabel">{{ __('Map') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modal-main-map" style="height: 600px; width: 100%;"></div>
        </div>
      </div>
    </div>
  </div>
  @include('frontend.partials.map-modal')
@endsection

@section('script')
  <!-- Map JS -->
  @if ($basicInfo->google_map_api_key_status == 1)
    <script src="{{ asset('assets/front/js/api-search-2.js') }}"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps_api_key') }}&libraries=places&callback=initMap"
      async defer></script>
  @endif
  <script src="{{ asset('assets/front/js/map-hotel.js') }}"></script>
  <script>
    "use strict";
    var featured_contents = {!! json_encode($featured_contents) !!};
    var hotel_contentss = {!! json_encode($currentPageData->items()) !!};
    var searchUrl = "{{ route('frontend.lokasi') }}";
    var getStateUrl = "{{ url('get-state') }}";
    var getCityUrl = "{{ url('get-city') }}";
    var getAddress = "{{ url('get-address') }}";
  </script>
  <script src="{{ asset('assets/front/js/hotel-search.js') }}"></script>
@endsection
