@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Perahu') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
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
        <a href="#">{{ __('Edit Perahu') }}</a>
      </li>
    </ul>
  </div>

  @php
    $vendorId = Auth::guard('vendor')->user()->id;

    if ($vendorId) {
        $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId);

        if (!empty($current_package) && !empty($current_package->features)) {
            $permissions = json_decode($current_package->features, true);
        } else {
            $permissions = null;
        }
    } else {
        $permissions = null;
    }
  @endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Perahu') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.perahu_management.perahu', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
          @php
            $dContent = App\Models\RoomContent::where('room_id', $room->id)
                ->where('language_id', $defaultLang->id)
                ->first();
            $slug = !empty($dContent) ? $dContent->slug : '';
          @endphp
          @if ($dContent)
            <a class="btn btn-success btn-sm float-right mr-1 d-inline-block"
              href=" {{ route('frontend.perahu.details', ['slug' => $slug, 'id' => $room->id]) }}" target="_blank">
              <span class="btn-label">
                <i class="fas fa-eye"></i>
              </span>
              {{ __('Preview') }}
            </a>
            <a class="btn btn-primary btn-sm float-right mr-1 d-inline-block"
              href="{{ route('vendor.perahu.packages.index', ['perahu_id' => $room->id]) }}">
              <span class="btn-label">
                <i class="fas fa-box"></i>
              </span>
              {{ __('Manage Packages') }}
            </a>
          @endif

        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 offset-lg-1">

              <div class="alert alert-danger pb-1 dis-none" id="roomErrors">
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') . '*' }}</strong></label>
                <div class="row">
                  <div class="col-12">
                    <table class="table table-striped" id="imgtable">
                      @foreach ($room->room_galleries as $item)
                        <tr class="trdb table-row" id="trdb{{ $item->id }}">
                          <td>
                            <div class="">
                              <img class="thumb-preview wf-150"
                                src="{{ asset('assets/img/perahu/room-gallery/' . $item->image) }}" alt="Ad Image">
                            </div>
                          </td>
                          <td>
                            <i class="fa fa-times rmvbtndb" data-indb="{{ $item->id }}"></i>
                          </td>
                        </tr>
                      @endforeach
                    </table>
                  </div>
                </div>
                <form action="#" id="my-dropzone" enctype="multipart/formdata" class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                  <input type="hidden" value="{{ $room->id }}" name="room_id">
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
                <p class="text-warning">
                <p class="text-warning">
                  {{ __('You can upload maximum') }}{{ __(' ') }}
                  {{ min(10, $current_package->number_of_images_per_room) }}{{ __(' ') }}{{ __('images under one perahu') }}
                </p>
                </p>
              </div>

              <form id="roomForm" action="{{ route('vendor.perahu_management.update_perahu', $room->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="">{{ __('Featured Image') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        <img
                          src="{{ $room->feature_image ? asset('assets/img/perahu/featureImage/' . $room->feature_image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="uploaded-img">
                      </div>
                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="thumbnail">
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <h4 class="mb-3">{{ __('Atribut Utama Kapal') }}</h4>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Nama KM (Kapal Motor)') . '*' }}</label>
                      <input type="text" class="form-control" name="nama_km" placeholder="Contoh: KM Pesona Laut" required value="{{ $room->nama_km }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jumlah Kamar Tidur') }}</label>
                      <input type="number" class="form-control" name="bedroom_count" value="{{ $room->bedroom_count }}" min="0">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jumlah Toilet') }}</label>
                      <input type="number" class="form-control" name="toilet_count" value="{{ $room->toilet_count }}" min="0">
                    </div>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-lg-12">
                    <h4 class="mb-3">{{ __('Spesifikasi Kapal') }}</h4>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Nama Kapten') }}</label>
                      <input type="text" class="form-control" name="captain_name"
                        value="{{ $room->captain_name }}" placeholder="{{ __('Nama Kapten') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Mesin 1') }}</label>
                      <input type="text" class="form-control" name="engine_1" value="{{ $room->engine_1 }}"
                        placeholder="{{ __('Mesin 1') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Mesin 2') }}</label>
                      <input type="text" class="form-control" name="engine_2" value="{{ $room->engine_2 }}"
                        placeholder="{{ __('Mesin 2') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Panjang Kapal (meter)') }}</label>
                      <input type="text" class="form-control" name="boat_length" value="{{ $room->boat_length }}"
                        placeholder="{{ __('Misal: 15') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Lebar Kapal (meter)') }}</label>
                      <input type="text" class="form-control" name="boat_width" value="{{ $room->boat_width }}"
                        placeholder="{{ __('Misal: 4') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kapasitas Orang') . '*' }}</label>
                      <input type="number" class="form-control" name="adult" value="{{ $room->adult }}"
                        placeholder="{{ __('Masukkan Kapasitas Orang') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kapasitas Tambahan') . '*' }}</label>
                      <input type="number" class="form-control" name="children" value="{{ $room->children }}"
                        placeholder="{{ __('Masukkan Kapasitas Tambahan') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Jumlah Mesin') . '*' }}</label>
                      <input type="number" class="form-control" name="bed" value="{{ $room->bed }}"
                        placeholder="{{ __('Masukkan Jumlah Mesin') }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Jumlah Kru') . '*' }}</label>
                      <input type="number" class="form-control" name="bathroom" value="{{ $room->bathroom }}"
                        placeholder="{{ __('Masukkan Jumlah Kru') }}">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <h4 class="mb-3">{{ __('Fasilitas & Detail Tambahan') }}</h4>
                  </div>
                  
                  {{-- Fishing & Safety --}}
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Umpan') }}</label>
                      <select name="bait" class="form-control">
                        <option value="0" @selected($room->bait == 0)>{{ __('Tidak Ada') }}</option>
                        <option value="1" @selected($room->bait == 1)>{{ __('Tersedia') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Alat Pancing') }}</label>
                      <select name="fishing_gear" class="form-control">
                        <option value="0" @selected($room->fishing_gear == 0)>{{ __('Tidak Ada') }}</option>
                        <option value="1" @selected($room->fishing_gear == 1)>{{ __('Tersedia') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Pelampung') }}</label>
                      <select name="life_jacket" class="form-control">
                        <option value="0" @selected($room->life_jacket == 0)>{{ __('Tidak Ada') }}</option>
                        <option value="1" @selected($room->life_jacket == 1)>{{ __('Tersedia') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('AC') }}</label>
                      <select name="ac" class="form-control">
                        <option value="0" @selected($room->ac == 0)>{{ __('Tidak Ada') }}</option>
                        <option value="1" @selected($room->ac == 1)>{{ __('Tersedia') }}</option>
                      </select>
                    </div>
                  </div>

                  {{-- Catering --}}
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Makan Pagi') }}</label>
                      <select name="breakfast" class="form-control">
                        <option value="0" @selected($room->breakfast == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->breakfast == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Makan Siang') }}</label>
                      <select name="lunch" class="form-control">
                        <option value="0" @selected($room->lunch == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->lunch == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Makan Malam') }}</label>
                      <select name="dinner" class="form-control">
                        <option value="0" @selected($room->dinner == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->dinner == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Air Minum') }}</label>
                      <select name="mineral_water" class="form-control">
                        <option value="0" @selected($room->mineral_water == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->mineral_water == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>

                  {{-- Other Utils --}}
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('WiFi') }}</label>
                      <select name="wifi" class="form-control">
                        <option value="0" @selected($room->wifi == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->wifi == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Listrik') }}</label>
                      <select name="electricity" class="form-control">
                        <option value="0" @selected($room->electricity == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->electricity == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kompor') }}</label>
                      <select name="stove" class="form-control">
                        <option value="0" @selected($room->stove == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->stove == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kulkas') }}</label>
                      <select name="refrigerator" class="form-control">
                        <option value="0" @selected($room->refrigerator == 0)>{{ __('Tidak') }}</option>
                        <option value="1" @selected($room->refrigerator == 1)>{{ __('Ya') }}</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>{{ __('Fitur Lainnya') }}</label>
                      <textarea name="other_features" class="form-control" rows="3" placeholder="{{ __('Sebutkan fasilitas lain jika ada...') }}">{{ $room->other_features }}</textarea>
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
                      <input type="number" class="form-control" name="price_day_1" value="{{ $room->price_day_1 }}"
                        placeholder="{{ __('Harga 1 Hari') }}" min="0" step="1">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jam Kumpul (1 Hari)') }}</label>
                      <input type="time" class="form-control" name="meet_time_day_1" value="{{ $room->meet_time_day_1 }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jam Kembali (1 Hari)') }}</label>
                      <input type="time" class="form-control" name="return_time_day_1" value="{{ $room->return_time_day_1 }}">
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <h6 class="mb-2 mt-2">{{ __('Paket 2 Hari') }}</h6>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Harga 2 Hari') }}</label>
                      <input type="number" class="form-control" name="price_day_2" value="{{ $room->price_day_2 }}"
                        placeholder="{{ __('Harga 2 Hari') }}" min="0" step="1">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jam Kumpul (2 Hari)') }}</label>
                      <input type="time" class="form-control" name="meet_time_day_2" value="{{ $room->meet_time_day_2 }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jam Kembali (2 Hari)') }}</label>
                      <input type="time" class="form-control" name="return_time_day_2" value="{{ $room->return_time_day_2 }}">
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <h6 class="mb-2 mt-2">{{ __('Paket 3 Hari') }}</h6>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Harga 3 Hari') }}</label>
                      <input type="number" class="form-control" name="price_day_3" value="{{ $room->price_day_3 }}"
                        placeholder="{{ __('Harga 3 Hari') }}" min="0" step="1">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jam Kumpul (3 Hari)') }}</label>
                      <input type="time" class="form-control" name="meet_time_day_3" value="{{ $room->meet_time_day_3 }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jam Kembali (3 Hari)') }}</label>
                      <input type="time" class="form-control" name="return_time_day_3" value="{{ $room->return_time_day_3 }}">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Uang Tanda Jadi/Deposit') }}</label>
                      <input type="number" class="form-control" name="deposit_amount"
                        value="{{ $room->deposit_amount }}" placeholder="{{ __('Uang Tanda Jadi/Deposit') }}" min="0" step="1">
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
                      <select name="hotel_id" class="form-control js-example-basic-single2">
                        <option selected disabled>{{ __('Pilih Lokasi Dermaga') }}</option>
                        @foreach ($hotels as $hotel)
                          <option @if ($room->hotel_id == $hotel->id) selected @endif value="{{ $hotel->id }}">
                            {{ @$hotel->hotel_contents->first()->title }}</option>
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
                      <label>{{ __('Status Perahu') . '*' }}</label>
                      <select name="status" id="status" class="form-control">
                        <option @if ($room->status == 1) selected @endif value="1">{{ __('Aktif') }}</option>
                        <option @if ($room->status == 0) selected @endif value="0">{{ __('Nonaktif') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Status Ketersediaan') }}</label>
                      <select name="availability_mode" class="form-control">
                        <option @if ($room->availability_mode == 1) selected @endif value="1">
                          {{ __('Real-time') }}
                        </option>
                        <option @if ($room->availability_mode == 2) selected @endif value="2">
                          {{ __('Persetujuan Host') }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('YouTube Video URL') }}</label>
                      <input type="text" class="form-control" name="video_url" value="{{ $room->video_url }}" placeholder="{{ __('Enter YouTube Video URL') }}">
                      <p class="text-info mt-2 mb-0">
                        <small>{{ __('If you enter a YouTube video URL, it will be embedded in the perahu details page.') }}</small>
                      </p>
                    </div>
                  </div>
                  </div>
                <input type="hidden" name="vendor_id" id="vendor_id"
                  value="{{ Auth::guard('vendor')->user()->id }}">

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    @php
                      $roomContent = App\Models\RoomContent::where('room_id', $room->id)
                          ->where('language_id', $language->id)
                          ->first();
                    @endphp
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
                                <label>{{ __('Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Title" value="{{ $roomContent ? $roomContent->title : '' }}">
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
                                  class="form-control js-example-basic-single2">
                                  <option selected disabled>{{ __('Select a Category') }}</option>

                                  @foreach ($types as $type)
                                    <option @selected(@$roomContent->room_category == $type->id) value="{{ $type->id }}">
                                      {{ $type->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            @php
                              $aminities = App\Models\Amenitie::where('language_id', $language->id)->get();
                              $hasaminitie = json_decode(@$roomContent->amenities);
                            @endphp
                            @if (count($aminities) > 0)
                              <div class="col-lg-12 ">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">

                                  <label>{{ __('Select Amenities') }} </label>
                                  <div class="dropdown-content" id="checkboxes">
                                    @if ($hasaminitie)
                                      @foreach ($aminities as $aminitie)
                                        @if (in_array($aminitie->id, $hasaminitie))
                                          <input
                                            @if ($roomAmenitieDown) class="input-checkbox  {{ $language->code }}_input-checkbox" @endif
                                            id="{{ $aminitie->id }}" type="checkbox"
                                            data-code ="{{ $language->code }}" data-listing_id ="{{ $room->id }}"
                                            data-language_id ="{{ $language->id }}"
                                            name="{{ $language->code }}_amenities[]" value="{{ $aminitie->id }}"
                                            checked>
                                          <label
                                            class="amenities-label {{ $language->direction == 1 ? 'ml-2 mr-0' : 'mr-2' }}"
                                            for="{{ $aminitie->id }}">{{ $aminitie->title }}</label>
                                        @else
                                          <input type="checkbox" name="{{ $language->code }}_amenities[]"
                                            value="{{ $aminitie->id }}" id="{{ $aminitie->id }}">
                                          <label
                                            class="amenities-label {{ $language->direction == 1 ? 'ml-2 mr-0' : 'mr-2' }}"
                                            for="{{ $aminitie->id }}">{{ $aminitie->title }}</label>
                                        @endif
                                      @endforeach
                                    @else
                                      <div class="dropdown-content" id="checkboxes">
                                        @foreach ($aminities as $aminitie)
                                          <input type="checkbox"id="{{ $aminitie->id }}"
                                            name="{{ $language->code }}_amenities[]" value="{{ $aminitie->id }}">
                                          <label
                                            class="amenities-label {{ $language->direction == 1 ? 'ml-2 mr-0' : 'mr-2' }}"
                                            for="{{ $aminitie->id }}">{{ $aminitie->title }}</label>
                                        @endforeach
                                      </div>
                                    @endif
                                  </div>
                                </div>
                              </div>
                            @endif


                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea class="form-control summernote" id="{{ $language->code }}_description"
                                  name="{{ $language->code }}_description" data-height="300">{{ @$roomContent->description }}</textarea>
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
                                  placeholder="Enter Meta Keywords" data-role="tagsinput"
                                  value="{{ $roomContent ? @$roomContent->meta_keyword : '' }}">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description">{{ $roomContent ? @$roomContent->meta_description : '' }}</textarea>
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
              <button type="submit" form="roomForm" class="btn btn-primary">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/admin/js/feature.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-dropzone.js') }}"></script>
  <script src="{{ asset('assets/admin/js/admin-room.js') }}"></script>
@endsection

@section('variables')
  <script>
    "use strict";
    var storeUrl = "{{ route('vendor.perahu_management.perahu.imagesstore') }}";
    var removeUrl = "{{ route('vendor.perahu_management.perahu.imagermv') }}";
    var rmvdbUrl = "{{ route('vendor.perahu_management.perahu.imgdbrmv') }}";
    var updateAminitie = "{{ route('vendor.perahu_management.update_amenities') }}"
    var galleryImages = {{ max(0, min(10, $current_package->number_of_images_per_room) - count($room->room_galleries)) }};
    var languages = {!! json_encode($languages) !!};

  </script>
@endsection
