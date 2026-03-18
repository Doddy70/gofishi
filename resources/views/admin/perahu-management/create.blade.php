@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Perahu') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Perahu Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Perahu') }}</a>
      </li>
    </ul>
  </div>

  @php
    $vendorId = $vendor_id;

    if ($vendorId == 0) {
        $numberoffImages = 99999999;
        $can_room_add = 1;
    } else {
        $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId);

        if ($current_package != '[]') {
            $numberoffImages = $current_package->number_of_images_per_room;
        } else {
            $numberoffImages = 0;
        }
        if (!empty($current_package) && !empty($current_package->features)) {
            $permissions = json_decode($current_package->features, true);
        } else {
            $permissions = null;
        }
    }

  @endphp


  <div class="row">
    <div class="col-md-12">
      @if ($vendorId != 0)
        @if ($current_package != '[]')
          @if (vendorTotalAddedRoom($vendorId) >= $current_package->number_of_room)
            <div class="alert alert-warning">
              {{ __('You cannot add more perahu for this vendor. Vendor will need to upgrade his plan') }}
            </div>
            @php
              $can_room_add = 2;
            @endphp
          @else
            @php
              $can_room_add = 1;
            @endphp
          @endif
        @else
          @php
            $pendingMemb = \App\Models\Membership::query()
                ->where([['vendor_id', '=', Auth::id()], ['status', 0]])
                ->whereYear('start_date', '<>', '9999')
                ->orderBy('id', 'DESC')
                ->first();
            $pendingPackage = isset($pendingMemb)
                ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id)
                : null;
          @endphp
          @if ($pendingPackage)
            <div class="alert alert-warning">
              {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
            </div>
            <div class="alert alert-warning">
              <strong>{{ __('Pending Package') . ':' }} </strong> {{ $pendingPackage->title }}
              <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
              <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
            </div>
          @else
            @php
              $newMemb = \App\Models\Membership::query()
                  ->where([['vendor_id', '=', Auth::id()], ['status', 0]])
                  ->first();
            @endphp
            @if ($newMemb)
              <div class="alert alert-warning">
                {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
              </div>
            @endif
            <div class="alert alert-warning">
              {{ __('Please purchase a new package to add Perahu.') }}
            </div>
          @endif
          @php
            $can_room_add = 0;
          @endphp
        @endif
      @endif
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Perahu') }}</div>
        </div>
        <div class="card-body">

          <div class="row">
            <div class="col-lg-10 offset-lg-1">


              <div class="alert alert-danger pb-1 dis-none" id="roomErrors">
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') . '*' }}</strong></label>
                <form action="{{ route('admin.perahu_management.perahu.imagesstore') }}" id="my-dropzone"
                  enctype="multipart/formdata" class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
                @if ($vendorId != 0)
                  @if ($current_package != '[]')
                    @if (vendorTotalAddedRoom($vendorId) <= $current_package->number_of_room)
                      <p class="text-warning">
                        {{ __('You can upload maximum') }}{{ __(' ') }}
                        {{ $current_package->number_of_images_per_room }}{{ __(' ') }}{{ __('images under one perahu') }}
                      </p>
                    @endif
                  @endif
                @endif
              </div>

              <form id="roomForm" action="{{ route('admin.perahu_management.store_perahu') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="">{{ __('Featured Image') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="feature_image">
                        </div>
                      </div>
                    </div>
                  </div>



                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('YouTube Video URL') }}</label>
                        <input type="url" class="form-control" name="video_url" placeholder="{{ __('Enter YouTube Video URL') }}">
                        <small class="text-muted">{{ __('Contoh: https://www.youtube.com/watch?v=xxxx') }}</small>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <h4 class="mb-3">{{ __('Atribut Kapal') }}</h4>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Nama Kapten') }}</label>
                        <input type="text" class="form-control" name="captain_name" placeholder="{{ __('Nama Kapten') }}">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Mesin 1') }}</label>
                        <input type="text" class="form-control" name="engine_1" placeholder="{{ __('Mesin 1') }}">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Mesin 2') }}</label>
                        <input type="text" class="form-control" name="engine_2" placeholder="{{ __('Mesin 2') }}">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Kapasitas Orang') . '*' }}</label>
                        <input type="number" class="form-control" name="adult" placeholder="{{ __('Masukkan Kapasitas Orang') }}">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Kapasitas Tambahan') . '*' }}</label>
                        <input type="number" class="form-control" name="children" value="0"
                          placeholder="{{ __('Masukkan Kapasitas Tambahan') }}">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Jumlah Mesin') . '*' }}</label>
                        <input type="number" class="form-control" name="bed" placeholder="{{ __('Masukkan Jumlah Mesin') }}">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Jumlah Kru') . '*' }}</label>
                        <input type="number" class="form-control" name="bathroom"
                          placeholder="{{ __('Masukkan Jumlah Kru') }}">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <h4 class="mb-3">{{ __('Harga & Jadwal') }}</h4>
                    </div>
                    <div class="col-lg-12">
                      <h6 class="mb-2">{{ __('Paket 1 Hari') }}</h6>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Harga 1 Hari') }}</label>
                        <input type="number" class="form-control" name="price_day_1" placeholder="{{ __('Harga 1 Hari') }}"
                          min="0" step="1">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Jam Kumpul (1 Hari)') }}</label>
                        <input type="time" class="form-control" name="meet_time_day_1">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Jam Kembali (1 Hari)') }}</label>
                        <input type="time" class="form-control" name="return_time_day_1">
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <h6 class="mb-2 mt-2">{{ __('Paket 2 Hari') }}</h6>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Harga 2 Hari') }}</label>
                        <input type="number" class="form-control" name="price_day_2" placeholder="{{ __('Harga 2 Hari') }}" min="0" step="1">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Jam Kumpul (2 Hari)') }}</label>
                        <input type="time" class="form-control" name="meet_time_day_2">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Jam Kembali (2 Hari)') }}</label>
                        <input type="time" class="form-control" name="return_time_day_2">
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <h6 class="mb-2 mt-2">{{ __('Paket 3 Hari') }}</h6>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Harga 3 Hari') }}</label>
                        <input type="number" class="form-control" name="price_day_3" placeholder="{{ __('Harga 3 Hari') }}" min="0" step="1">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Jam Kumpul (3 Hari)') }}</label>
                        <input type="time" class="form-control" name="meet_time_day_3">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>{{ __('Jam Kembali (3 Hari)') }}</label>
                        <input type="time" class="form-control" name="return_time_day_3">
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Uang Tanda Jadi/Deposit') }}</label>
                        <input type="number" class="form-control" name="deposit_amount"
                          placeholder="{{ __('Uang Tanda Jadi/Deposit') }}" min="0" step="1">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <h4 class="mb-3">{{ __('Lokasi Dermaga') }}</h4>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Pilih Lokasi Dermaga') . '*' }}</label>
                        <select name="hotel_id" class="form-control js-example-basic-single2 select2">
                          <option selected disabled>{{ __('Pilih Lokasi Dermaga') }}</option>
                          @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ @$hotel->hotel_contents->first()->title }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <h4 class="mb-3">{{ __('Status & Ketersediaan') }}</h4>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Status Perahu') . '*' }} </label>
                        <select name="status" id="status" class="form-control">
                          <option value="1">{{ __('Aktif') }}</option>
                          <option selected value="0">{{ __('Nonaktif') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>{{ __('Status Ketersediaan') }}</label>
                        <select name="availability_mode" class="form-control">
                          <option value="1" selected>{{ __('Real-time') }}</option>
                          <option value="2">{{ __('Persetujuan Host') }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $vendorId }}">
                  <div id="accordion" class="mt-3">
                    @foreach ($languages as $language)
                      <div class="version">
                        <div class="version-header" id="heading{{ $language->id }}">
                          <h5 class="mb-0">
                            <button type="button" class="btn btn-link" data-toggle="collapse"
                              data-target="#collapse{{ $language->id }}"
                              aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                              aria-controls="collapse{{ $language->id }}">
                              {{ $language->name . __(' Language') }}
                              {{ $language->is_default == 1 ? '(Default)' : '' }}
                            </button>
                          </h5>
                        </div>

                        <div id="collapse{{ $language->id }}"
                          class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                          aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                          <div class="version-body {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label>{{ __('Title') . '*' }} </label>
                                  <input type="text" class="form-control" name="{{ $language->code }}_title"
                                    placeholder="{{ __('Enter Title') }}">
                                </div>
                              </div>

                              <div class="col-lg-6">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  @php
                                    $types = App\Models\RoomCategory::where('language_id', $language->id)
                                        ->where('status', 1)
                                        ->orderBy('serial_number', 'asc')
                                        ->get();
                                  @endphp

                                  <label>{{ __('Category') . '*' }}</label>
                                  <select name="{{ $language->code }}_room_category"
                                    class="form-control js-example-basic-single2 select2">
                                    <option selected disabled>{{ __('Select a Category') }}</option>

                                    @foreach ($types as $type)
                                      <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              @php
                                $aminities = App\Models\Amenitie::where('language_id', $language->id)->get();
                              @endphp
                              @if (count($aminities) > 0)
                                <div class="col-lg-12">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">


                                    <label>{{ __('Select Amenities') . '*' }} </label>
                                    <div class="dropdown-content" id="checkboxes">
                                      @foreach ($aminities as $amenity)
                                        <input type="checkbox" id="{{ $amenity->id }}"
                                          name="{{ $language->code }}_amenities[]" value="{{ $amenity->id }}">
                                        <label
                                          class="amenities-label {{ $language->direction == 1 ? 'ml-2 mr-0' : 'mr-2' }}"
                                          for="{{ $amenity->id }}">{{ $amenity->title }}</label>
                                      @endforeach
                                    </div>
                                  </div>
                                </div>
                              @endif

                            </div>

                            <div class="row">
                              <div class="col-lg-12">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label>{{ __('Description') . '*' }}</label>
                                  <textarea id="{{ $language->code }}_description" class="form-control summernote"
                                    name="{{ $language->code }}_description" data-height="300"></textarea>
                                  <small class="text-muted d-block mt-2">
                                    Isi detail perahu: Nama Kapten, Mesin 1/2, kapasitas, jadwal kumpul/kembali, dan info dermaga.
                                  </small>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-12">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label>{{ __('Meta Keywords') }}</label>
                                  <input class="form-control" name="{{ $language->code }}_meta_keyword"
                                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-12">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label>{{ __('Meta Description') }}</label>
                                  <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                    placeholder="{{ __('Enter Meta Description') }}"></textarea>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col">
                                @php $currLang = $language; @endphp

                                @foreach ($languages as $language)
                                  @continue($language->id == $currLang->id)

                                  <div class="form-check py-0">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="checkbox"
                                        onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                      <span class="form-check-sign">{{ __('Clone for') }} <strong
                                          class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                        {{ __('language') }}</span>
                                    </label>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <div id="sliders">
                  </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="roomForm" data-can_room_add="{{ $can_room_add }}" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    'use strict';
    var storeUrl = "{{ route('admin.perahu_management.perahu.imagesstore') }}";
    var removeUrl = "{{ route('admin.perahu_management.perahu.imagermv') }}";
    var galleryImages = {{ $numberoffImages }};
    var languages = {!! json_encode($languages) !!};

  </script>

  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-room.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-dropzone.js') }}"></script>
@endsection
