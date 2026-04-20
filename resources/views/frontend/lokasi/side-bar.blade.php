<div class="offcanvas-body p-0">

  <aside class="widget-area  px-20">

    <div class="widget widget-select py-20">
      <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#select">
          {{ __('Filter Pencarian') }}
        </button>
      </h5>
      <div id="select" class="collapse show">
        <div class="mt-20">
          <div class="form-group icon-end mb-20">
            <input type="text" class="form-control" value="{{ request()->input('title') }}" id="searchBytTitle"
              name="title" placeholder="{{ __('Cari nama lokasi') }}">
            <label class="mb-0 color-primary"><i class="fal fa-search"></i></label>
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
        </div>
      </div>
    </div>

    <div class="widget widget-amenities py-20">
      <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#lokasiLain">
          {{ __('Lokasi Lainnya') }}
        </button>
      </h5>
      <div id="lokasiLain" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
          <ul class="list-group" data-toggle-list="lokasiToggle" data-toggle-show="6">
            @foreach ($hotel_contentss->take(8) as $hotel)
              <li class="list-item">
                <a href="{{ route('frontend.lokasi.details', ['slug' => $hotel->slug, 'id' => $hotel->id]) }}">
                  {{ $hotel->title }}
                </a>
              </li>
            @endforeach
          </ul>
          <span class="show-more font-sm" data-toggle-btn="toggleListBtn">{{ __('Show More') . '+' }} </span>
        </div>
      </div>
    </div>

    <div class="cta mb-30">
      <a href="{{ route('frontend.lokasi') }}" class="btn btn-lg btn-primary icon-start w-100"><i
          class="fal fa-sync-alt"></i>{{ __('Reset Filter') }}</a>
    </div>
  </aside>
</div>
