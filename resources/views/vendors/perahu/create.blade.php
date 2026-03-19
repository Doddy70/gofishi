@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Perahu') }}</h4>
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
        <a href="#">{{ __('Add Perahu') }}</a>
      </li>
    </ul>
  </div>

  @php
    $vendorId = Auth::guard('vendor')->user()->id;
    $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId);
  @endphp

  <div class="row">
    <div class="col-md-12">
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

              {{-- Gallery Images --}}
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') . '*' }}</strong></label>
                <form action="{{ route('vendor.perahu_management.perahu.imagesstore') }}" id="my-dropzone"
                  enctype="multipart/form-data" class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
              </div>

              <form id="roomForm" action="{{ route('vendor.perahu_management.store_perahu') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="sliders"></div>
                
                {{-- Main Info --}}
                <div class="row">
                  <div class="col-lg-12">
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
                </div>

                <div class="row mt-4">
                  <div class="col-lg-12">
                    <h4 class="mb-3"><strong>{{ __('Atribut Utama Kapal') }}</strong></h4>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Nama KM (Kapal Motor)') . '*' }}</label>
                      <input type="text" class="form-control" name="nama_km" placeholder="Contoh: KM Pesona Laut" required>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jumlah Kamar Tidur') }}</label>
                      <input type="number" class="form-control" name="bedroom_count" value="0" min="0">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Jumlah Toilet') }}</label>
                      <input type="number" class="form-control" name="toilet_count" value="0" min="0">
                    </div>
                  </div>
                </div>


                {{-- Boat Attributes --}}
                <div class="row mt-2">
                  <div class="col-lg-12">
                    <h4 class="mb-3"><strong>{{ __('Spesifikasi Kapal') }}</strong></h4>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Nama Kapten') }}</label>
                      <input type="text" class="form-control" name="captain_name" placeholder="Nama Kapten">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Mesin 1') }}</label>
                      <input type="text" class="form-control" name="engine_1" placeholder="Mesin 1">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Mesin 2') }}</label>
                      <input type="text" class="form-control" name="engine_2" placeholder="Mesin 2">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Panjang (m)') }}</label>
                      <input type="text" class="form-control" name="boat_length">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Lebar (m)') }}</label>
                      <input type="text" class="form-control" name="boat_width">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kapasitas (Orang)') }}</label>
                      <input type="number" class="form-control" name="adult">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Jumlah Kru') }}</label>
                      <input type="number" class="form-control" name="crew_count">
                    </div>
                  </div>
                </div>

                {{-- Pricing & Schedule per Package --}}
                <div class="row mt-4">
                  <div class="col-lg-12">
                    <h4 class="mb-3"><strong>{{ __('Alur Sewa & Harga') }}</strong></h4>
                  </div>
                  
                  {{-- Package 1 Day --}}
                  <div class="col-lg-12"><h5>{{ __('Paket 1 Hari') }}</h5></div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Harga (Rp)') }}</label>
                      <input type="number" class="form-control" name="price_day_1">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kumpul Dermaga') }}</label>
                      <input type="time" class="form-control" name="meet_time_day_1">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kembali Dermaga') }}</label>
                      <input type="time" class="form-control" name="return_time_day_1">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Area Rute') }}</label>
                      <input type="text" class="form-control" name="area_day_1" placeholder="Misal: P. Matahari">
                    </div>
                  </div>

                  {{-- Package 2 Days --}}
                  <div class="col-lg-12 mt-2"><h5>{{ __('Paket 2 Hari') }}</h5></div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Harga (Rp)') }}</label>
                      <input type="number" class="form-control" name="price_day_2">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kumpul Dermaga') }}</label>
                      <input type="time" class="form-control" name="meet_time_day_2">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kembali Dermaga') }}</label>
                      <input type="time" class="form-control" name="return_time_day_2">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Area Rute') }}</label>
                      <input type="text" class="form-control" name="area_day_2" placeholder="Misal: Karang Tandes">
                    </div>
                  </div>

                  {{-- Package 3 Days --}}
                  <div class="col-lg-12 mt-2"><h5>{{ __('Paket 3 Hari') }}</h5></div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Harga (Rp)') }}</label>
                      <input type="number" class="form-control" name="price_day_3">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kumpul Dermaga') }}</label>
                      <input type="time" class="form-control" name="meet_time_day_3">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Kembali Dermaga') }}</label>
                      <input type="time" class="form-control" name="return_time_day_3">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Area Rute') }}</label>
                      <input type="text" class="form-control" name="area_day_3" placeholder="Misal: P. Bulan">
                    </div>
                  </div>
                </div>

                {{-- Booking Settings --}}
                <div class="row mt-4">
                  <div class="col-lg-12">
                    <h4 class="mb-3"><strong>{{ __('Pengaturan Booking & Deposit') }}</strong></h4>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Tipe Booking') }}</label>
                      <select name="booking_type" class="form-control">
                        <option value="direct">{{ __('Real-Time (Langsung)') }}</option>
                        <option value="approval">{{ __('Persetujuan Host (Approval)') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Tipe Deposit') }}</label>
                      <select name="deposit_type" class="form-control">
                        <option value="full">{{ __('Bayar Lunas') }}</option>
                        <option value="percentage">{{ __('Persentase (%)') }}</option>
                        <option value="fixed">{{ __('Nilai Tetap (Rp)') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Nilai Deposit') }}</label>
                      <input type="number" class="form-control" name="deposit_amount" value="0">
                    </div>
                  </div>
                </div>

                {{-- Amenities (Checkboxes or Select) --}}
                {{-- Add location inputs (Google Maps) if needed --}}

                <div class="row mt-4">
                  <div class="col-12 text-center">
                    <button type="submit" id="submitBtn" class="btn btn-success">
                      {{ __('Simpan Perahu') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    "use strict";
    var storeUrl = "{{ route('vendor.perahu_management.perahu.imagesstore') }}";
    var removeUrl = "{{ route('vendor.perahu_management.perahu.imagermv') }}";
  </script>
  <script src="{{ asset('assets/js/dropzone-create.js') }}"></script>
@endsection
