<div class="offcanvas-body p-0">
  <aside class="widget-area widget-area-style-2 px-20">

    <div class="widget widget-amenities py-20">
      <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#amenities">
          {{ __('Kategori Perahu') }}
        </button>
      </h5>
      <div id="amenities" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
          <ul class="list-group" data-toggle-list="categoriesToggle" data-toggle-show="5">
            <li class="list-item @if (request()->input('category') == null) active @endif">
              <a href="#" class="category-toggle @if (request()->input('category') == null) active @endif" id="">
                {{ __('Semua') }}
              </a>
            </li>
            @foreach ($categories as $categorie)
              <li class="list-item @if (request()->input('category') == $categorie->slug) active @endif">
                <a href="#" class="category-toggle" id="{{ $categorie->slug }}">
                  {{ $categorie->name }}
                </a>
              </li>
            @endforeach
          </ul>
          <span class="show-more font-sm" data-toggle-btn="toggleListBtn">{{ __('Tampilkan Lainnya') . '+' }} </span>
        </div>
      </div>
    </div>

    <div class="widget widget-price py-20">
      <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#proximity">
          {{ __('Lokasi Saya') }}
        </button>
      </h5>
      <div id="proximity" class="collapse show">
        <div class="mt-20">
          <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-3" id="btnGetCurrentLocation">
            <i class="fal fa-map-marker-alt me-2"></i> {{ __('Gunakan Lokasi Saat Ini') }}
          </button>
          
          <div id="radiusFilter" style="display: {{ request()->input('lat') ? 'block' : 'none' }};">
            <label class="form-label font-sm">{{ __('Radius Jangkauan') }}: <span id="radiusValText">{{ request()->input('radius', 50) }}</span> km</label>
            <input type="range" class="form-range" min="1" max="200" step="1" id="radiusRange" name="radius" value="{{ request()->input('radius', 50) }}">
          </div>

          <input type="hidden" name="lat" id="userLat" value="{{ request()->input('lat') }}">
          <input type="hidden" name="lng" id="userLng" value="{{ request()->input('lng') }}">
        </div>
      </div>
    </div>

    <div class="widget widget-price py-20">
      <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#options"
          aria-expanded="true" aria-controls="options">
          {{ __('Filter Pencarian') }}
        </button>
      </h5>
      <div id="options" class="collapse show">
        <div class="mt-20">
          <div class="form-group icon-end mb-20">
            <input type="text" class="form-control" value="{{ request()->input('title') }}" id="searchBytTitle"
              name="title" placeholder="{{ __('Cari nama perahu') }}">
            <label class="mb-0 color-primary"><i class="fal fa-search"></i></label>
          </div>
          <div class="col-md-12 col-xxl-12">
            <div class="form-group select-area mb-20">
              <select class="form-control select2 hotelDropdown" id="hotelDropdown">
                <option value="" selected disabled>{{ __('Pilih Lokasi Dermaga') }}</option>
                <option value="">{{ __('Semua') }}</option>
                @foreach ($hotels as $hotel)
                  <option @if (request()->input('hotelId') == $hotel->id) selected @endif value="{{ $hotel->id }}">
                    {{ @$hotel->title }}</option>
                @endforeach
              </select>
            </div>
          </div>

          @if ($countries->count() > 0)
            <div class="form-group mb-20">
              <select class="form-control select2 countryDropdown" id="countryDropdown">
                <option value="" selected disabled>{{ __('Pilih Negara') }}</option>
                <option value="">{{ __('Semua') }}</option>
                @foreach ($countries as $country)
                  <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
              </select>
            </div>
          @endif

          @if ($states->count() > 0)
            <div class="form-group mb-20 hide_state">
              <select class="form-control select2 stateDropdown" id="stateDropdown">
                <option value="" selected disabled>{{ __('Pilih Provinsi') }}</option>
                <option value="">{{ __('Semua') }}</option>
                @foreach ($states as $state)
                  <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
              </select>
            </div>
          @endif

          @if ($cities->count() > 0)
            <div class="form-group mb-20">
              <select class="form-control select2 cityDropdown" id="cityDropdown">
                <option value="" selected disabled>{{ __('Pilih Kota') }}</option>
                <option value="">{{ __('Semua') }}</option>
                @foreach ($cities as $city)
                  <option @if (request()->input('city') == $city->id) selected @endif value="{{ $city->id }}">
                    {{ $city->name }}</option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="form-group mb-20">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="ageFilter" name="age_confirmation" {{ request()->input('age_confirmation') ? 'checked' : '' }}>
              <label class="form-check-label" for="ageFilter">
                {{ __('Saya berusia di atas 17 tahun') }}
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="widget widget-amenities py-20">
      <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#lokasiLainnya">
          {{ __('Lokasi Dermaga Lainnya') }}
        </button>
      </h5>
      <div id="lokasiLainnya" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
          <ul class="list-group" data-toggle-list="lokasiToggle" data-toggle-show="6">
            @foreach ($hotels->take(8) as $hotel)
              <li class="list-item">
                <a href="{{ route('frontend.perahu', ['hotelId' => $hotel->id]) }}">
                  {{ $hotel->title }}
                </a>
              </li>
            @endforeach
          </ul>
          <span class="show-more font-sm" data-toggle-btn="toggleListBtn">{{ __('Tampilkan Lainnya') . '+' }} </span>
        </div>
      </div>
    </div>
    <div class="cta mb-30">
      <a href="{{ route('frontend.perahu') }}" class="btn btn-lg btn-primary icon-start w-100"><i
          class="fal fa-sync-alt"></i>{{ __('Reset Filter') }}</a>
    </div>
  </aside>
</div>
