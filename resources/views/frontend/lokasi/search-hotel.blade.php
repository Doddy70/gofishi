@if (count($featured_contents) < 1 && count($hotel_contentss) < 1)
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

    @foreach ($hotel_contentss as $hotel_content)
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


    {{-- @if ($hotel_contentss->count() / $perPage > 1)
      <nav class="pagination-nav mb-40" data-aos="fade-up">
        <ul class="pagination justify-content-center">
          @if (request()->input('page'))
            @if (request()->input('page') != 1)
              <li class="page-item">
                <a class="page-link" data-page="{{ request()->input('page') - 1 }}" aria-label="Previous">
                  <i class="far fa-angle-left"></i>
                </a>
              </li>
            @else
              <li class="page-item disabled">
                <a class="page-link" aria-label="Previous" tabindex="-1" aria-disabled="true">
                  <i class="far fa-angle-left"></i>
                </a>
              </li>
            @endif
          @endif

          @if ($hotel_contentss->count() / $perPage > 1)
            @for ($i = 1; $i <= ceil($hotel_contentss->count() / $perPage); $i++)
              <li class="page-item @if (request()->input('page') == $i) active @endif">
                <a class="page-link" data-page="{{ $i }}">{{ $i }}</a>
              </li>
            @endfor
          @endif
          @php
            $totalPages = ceil($hotel_contentss->count() / $perPage);
          @endphp

          @if (request()->input('page'))
            @if (request()->input('page') != $totalPages)
              <li class="page-item">
                <a class="page-link" data-page="{{ request()->input('page') + 1 }}" aria-label="Previous">
                  <i class="far fa-angle-right"></i>
                </a>
              </li>
            @else
              <li class="page-item disabled">
                <a class="page-link" aria-label="Previous" tabindex="-1" aria-disabled="true">
                  <i class="far fa-angle-right"></i>
                </a>
              </li>
            @endif
          @endif
        </ul>
      </nav>
    @endif --}}
    @if ($hotel_contentss->lastPage() > 1)
      <div class="airbnb-pagination" data-aos="fade-up">
        @php 
          $totalPages = $hotel_contentss->lastPage(); 
          $currentPage = request()->input('page', 1);
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

        @if($currentPage < $totalPages)
          <a class="page-btn page-link" data-page="{{ $currentPage + 1 }}"><i class="far fa-angle-right"></i></a>
        @else
          <button class="page-btn disabled" disabled><i class="far fa-angle-right"></i></button>
        @endif
      </div>
    @endif

  </div>
@endif
<script>
  "use strict";
  var featured_contents = {!! json_encode($featured_contents) !!};
  var hotel_contentss = {!! json_encode($hotel_contentss->items()) !!};
</script>
