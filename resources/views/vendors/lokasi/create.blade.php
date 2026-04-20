@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">Tambah Lokasi Dermaga</h4>
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
        <a href="#">Manajemen Lokasi</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Tambah Lokasi Dermaga</a>
      </li>
    </ul>
  </div>

  @php
    $vendorId = Auth::guard('vendor')->user()->id;
    if ($vendorId) {
        $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId);
        $numberoffImages = ($current_package != '[]') ? $current_package->number_of_images_per_hotel : 0;
    }
  @endphp

  <div class="row">
    <div class="col-md-12">
      @if ($current_package != '[]')
        @if (vendorTotalAddedHotel($vendorId) >= $current_package->number_of_hotel)
          <div class="alert alert-warning">
            Kapasitas paket Anda telah penuh. Tidak bisa menambahkan lokasi baru, silakan perpanjang atau upgrade paket langganan Anda.
          </div>
          @php $can_hotel_add = 2; @endphp
        @else
          @php $can_hotel_add = 1; @endphp
        @endif
      @else
        @php $can_hotel_add = 0; @endphp
      @endif

      <div class="alert alert-danger pb-1 dis-none" id="hotelErrors"><ul></ul></div>

      {{-- ======= WIZARD STEP INDICATOR ======= --}}
      <div class="wizard-container mb-4">
        <ul class="wizard-steps">
          <li class="active" id="step-1-tab">
            <div class="step-num">1</div>
            <div class="step-text">Media & Foto</div>
          </li>
          <li id="step-2-tab">
            <div class="step-num">2</div>
            <div class="step-text">Info Dasar</div>
          </li>
          <li id="step-3-tab">
            <div class="step-num">3</div>
            <div class="step-text">Lokasi & Peta</div>
          </li>
          <li id="step-4-tab">
            <div class="step-num">4</div>
            <div class="step-text">Fasilitas & SEO</div>
          </li>
        </ul>
      </div>

      <form id="hotelForm" action="{{ route('vendor.lokasi_management.store_lokasi') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ======= STEP 1: MEDIA & GALERI ======= --}}
        <div class="wizard-step active" id="step-1">
          <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
              <h4 class="card-title fw-bold text-primary"><i class="fas fa-camera mr-2"></i>Langkah 1: Media & Galeri Dermaga</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12 mb-4">
                  <label class="font-weight-bold d-block mb-3 text-dark">Foto Utama Lokasi Dermaga <span class="text-danger">*</span></label>
                  <div class="photo-upload-placeholder" onclick="document.getElementById('feature_image_input').click()">
                    <i class="fas fa-image"></i>
                    <p class="font-weight-bold text-dark mb-1">Klik untuk unggah foto utama dermaga</p>
                    <p class="text-muted small">Foto ini akan menjadi sampul utama di halaman pencarian customer</p>
                    <div class="thumb-preview border rounded d-none mt-2" style="max-width: 300px;">
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img" style="max-width: 100%;">
                    </div>
                    <input type="file" id="feature_image_input" class="img-input d-none" name="feature_image" onchange="previewImage(this)">
                  </div>
                </div>

                <div class="col-lg-12 mt-4 pt-4 border-top">
                  <label class="mb-3 font-weight-bold h5 text-dark">Galeri Foto Dermaga <span class="text-danger">*</span></label>
                  <div id="dropzone-api-url" data-url="{{ route('vendor.lokasi_management.lokasi.imagesstore') }}"></div>
                  <div class="dropzone create border-primary mb-2" id="my-dropzone">
                    <div class="fallback"><input name="file" type="file" multiple /></div>
                  </div>
                  @if ($current_package != '[]')
                    <p class="text-warning small"><i class="fas fa-info-circle"></i> Maksimal {{ $numberoffImages }} foto.</p>
                  @endif
                </div>
              </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-end">
               <button type="button" class="btn btn-primary px-5 py-3 font-weight-bold rounded-pill shadow-sm" onclick="nextStep(2)">Lanjut ke Info Dasar <i class="fas fa-arrow-right ml-2"></i></button>
            </div>
          </div>
        </div>

        <input type="hidden" name="vendor_id" value="{{ $vendorId }}">
        <input type="hidden" name="can_hotel_add" value="{{ $can_hotel_add }}">
        <div class="d-none"><select name="stars"><option value="5" selected>5</option></select></div>

        @foreach ($languages as $language)
          @if ($language->is_default == 1)
            <div id="collapse{{ $language->id }}">
              {{-- ======= STEP 2: INFO DASAR ======= --}}
              <div class="wizard-step" id="step-2">
                <div class="card mb-4 shadow-sm border-0">
                  <div class="card-header bg-white border-bottom">
                    <h4 class="card-title fw-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Langkah 2: Informasi Dasar Dermaga</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="font-weight-bold">Nama Dermaga / Lokasi <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-lg border-primary" name="{{ $language->code }}_title" placeholder="Contoh: Dermaga Utama Karangasem">
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                          @php $categories = App\Models\HotelCategory::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get(); @endphp
                          <label class="font-weight-bold">Tipe Lokasi <span class="text-danger">*</span></label>
                          <select name="{{ $language->code }}_category_id" class="form-control js-example-basic-single2 border-primary">
                            <option selected disabled>-- Pilih Tipe --</option>
                            @foreach ($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                          <label class="font-weight-bold">Status Persetujuan <span class="text-danger">*</span></label>
                          <select name="status" class="form-control border-primary">
                            <option value="1">Diaktifkan (Aktif)</option>
                            <option selected value="0">Disembunyikan (Deactive)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill" onclick="nextStep(1)"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
                    <button type="button" class="btn btn-primary px-5 py-3 font-weight-bold rounded-pill shadow-sm" onclick="nextStep(3)">Lanjut ke Lokasi <i class="fas fa-arrow-right ml-2"></i></button>
                  </div>
                </div>
              </div>

              {{-- ======= STEP 3: LOKASI & PETA ======= --}}
              <div class="wizard-step" id="step-3">
                <div class="card mb-4 shadow-sm border-0">
                  <div class="card-header bg-white border-bottom">
                    <h4 class="card-title fw-bold text-primary"><i class="fas fa-map-marked-alt mr-2"></i>Langkah 3: Wilayah & Titik Jemput</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          @php $Countries = App\Models\Location\Country::where('language_id', $language->id)->get(); @endphp
                          <label class="font-weight-bold">Negara <span class="text-danger">*</span></label>
                          <select name="{{ $language->code }}_country_id" class="form-control js-example-basic-single3 border-primary" data-code="{{ $language->code }}">
                            @foreach ($Countries as $Country) <option value="{{ $Country->id }}" {{ $Country->name == 'Indonesia' ? 'selected' : '' }}>{{ $Country->name }}</option> @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 {{ $language->code }}_hide_state">
                        <div class="form-group">
                          @php $States = App\Models\Location\State::where('language_id', $language->id)->get(); @endphp
                          <label class="font-weight-bold">Provinsi <span class="text-danger">*</span></label>
                          <select name="{{ $language->code }}_state_id" class="form-control js-example-basic-single4 border-primary {{ $language->code }}_country_state_id" data-code="{{ $language->code }}">
                            <option selected disabled>Pilih Provinsi</option>
                            @foreach ($States as $State) <option value="{{ $State->id }}">{{ $State->name }}</option> @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          @php $cities = App\Models\Location\City::where('language_id', $language->id)->get(); @endphp
                          <label class="font-weight-bold">Kota/Kabupaten <span class="text-danger">*</span></label>
                          <select name="{{ $language->code }}_city_id" class="form-control js-example-basic-single5 border-primary {{ $language->code }}_state_city_id">
                            <option selected disabled>Pilih Kota</option>
                            @foreach ($cities as $City) <option value="{{ $City->id }}">{{ $City->name }}</option> @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="mt-4 p-4 border rounded shadow-sm" style="background-color: #fbfcfe; border-radius: 16px !important;">
                      <label class="font-weight-bold mb-3 h5 text-dark"> Cari Alamat atau Nama Dermaga</label>
                      <div class="input-group mb-2">
                        <div class="input-group-prepend"><span class="input-group-text bg-white border-primary"><i class="fas fa-map-pin text-danger"></i></span></div>
                        <input type="text" class="form-control form-control-lg border-primary" value="{{ old($language->code . '_address') }}" name="{{ $language->code }}_address" placeholder="Ketikkan nama jalan atau nama pelabuhan..." id="search-address">
                        <div class="input-group-append"><button type="button" class="btn btn-danger font-weight-bold px-4" data-toggle="modal" data-target="#GoogleMapModal"><i class="fas fa-bullseye mr-1"></i> Tentukan di Peta</button></div>
                      </div>
                      <input type="hidden" id="latitude" name="latitude"><input type="hidden" id="longitude" name="longitude">
                    </div>
                  </div>
                  <div class="card-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill" onclick="nextStep(2)"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
                    <button type="button" class="btn btn-primary px-5 py-3 font-weight-bold rounded-pill shadow-sm" onclick="nextStep(4)">Lanjut ke Fasilitas <i class="fas fa-arrow-right ml-2"></i></button>
                  </div>
                </div>
              </div>

              {{-- ======= STEP 4: FASILITAS & PENJELASAN ======= --}}
              <div class="wizard-step" id="step-4">
                <div class="card mb-4 shadow-sm border-0">
                  <div class="card-header bg-white border-bottom">
                    <h4 class="card-title fw-bold text-primary"><i class="fas fa-list mr-2"></i>Langkah 4: Fasilitas & Detail</h4>
                  </div>
                  <div class="card-body">
                    @php 
                      $aminities = App\Models\Amenitie::where('language_id', $language->id)->get();
                      $amenityIcons = ['Parkir' => 'fa-parking', 'Toilet' => 'fa-restroom', 'Kafe' => 'fa-coffee', 'Restoran' => 'fa-utensils', 'Ruang Tunggu' => 'fa-couch', 'Keamanan' => 'fa-shield-alt', 'Mushola' => 'fa-mosque', 'ATM' => 'fa-credit-card'];
                    @endphp
                    @if (count($aminities) > 0)
                      <div class="form-group mb-5">
                        <label class="font-weight-bold d-block mb-3 h5 text-dark">Lengkapi Fasilitas Dermaga <span class="text-danger">*</span></label>
                        <div class="amenity-grid">
                          @foreach ($aminities as $amenity)
                            @php $iconClass = 'fa-check-circle'; foreach($amenityIcons as $key => $val) { if(strpos(strtolower($amenity->title), strtolower($key)) !== false) { $iconClass = $val; break; } } @endphp
                            <div class="amenity-card" onclick="toggleAmenity(this, '{{ $amenity->id }}')">
                              <i class="fas {{ $iconClass }}"></i><span>{{ $amenity->title }}</span>
                              <input type="checkbox" name="{{ $language->code }}_aminities[]" value="{{ $amenity->id }}" id="check-{{ $amenity->id }}" style="display:none">
                            </div>
                          @endforeach
                        </div>
                      </div>
                    @endif

                    <div class="form-group border-top pt-4">
                      <label class="font-weight-bold h5 text-dark">Penjelasan Mendetail tentang Lokasi <span class="text-danger">*</span></label>
                      <textarea id="{{ $language->code }}_description" class="form-control summernote" name="{{ $language->code }}_description" data-height="300"></textarea>
                    </div>

                    <div class="form-group border-0 rounded-lg bg-light p-4 mt-5" style="border-radius: 16px !important;">
                       <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-search mr-1"></i> Optimasi Google (SEO) - Opsional</h5>
                       <div class="row">
                          <div class="col-lg-6"><label class="font-weight-bold small">Kata Kunci</label><input class="form-control border-primary" name="{{ $language->code }}_meta_keyword" placeholder="Contoh: dermaga sanur bali" data-role="tagsinput"></div>
                          <div class="col-lg-6"><label class="font-weight-bold small">Meta Deskripsi</label><textarea class="form-control border-primary" name="{{ $language->code }}_meta_description" rows="2"></textarea></div>
                       </div>
                    </div>

                    <div class="form-group mt-5">
                      <label class="font-weight-bold">Tanya Jawab Seputar Lokasi (FAQ)</label>
                      <div id="faq-container-{{$language->code}}"></div>
                      <button type="button" class="btn btn-sm btn-info mt-3" onclick="addFaq('{{$language->code}}')"><i class="fas fa-plus"></i> Tambah FAQ</button>
                    </div>
                  </div>
                  <div class="card-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill" onclick="nextStep(3)"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
                    <button type="submit" class="btn btn-success px-5 py-3 font-weight-bold rounded-pill shadow-sm"><i class="fas fa-check-circle mr-2"></i> Publikasikan Lokasi Dermaga</button>
                  </div>
                </div>
              </div>
            </div>
          @else
            <div class="card mb-3 border-0">
               <div class="card-header bg-light">
                 <button type="button" class="btn btn-link text-secondary font-weight-bold" data-toggle="collapse" data-target="#collapse{{ $language->id }}"><i class="fas fa-globe mr-1"></i> {{ $language->name }}</button>
               </div>
               <div id="collapse{{ $language->id }}" class="collapse">
                 <div class="card-body bg-white border">
                    <input type="text" class="form-control mb-3" name="{{ $language->code }}_title" placeholder="Title ({{ $language->name }})">
                    <textarea class="form-control summernote" name="{{ $language->code }}_description"></textarea>
                 </div>
               </div>
            </div>
          @endif
        @endforeach

        <div class="card mb-5 border-success"><div class="card-body bg-light">
          <h5 class="font-weight-bold text-success mb-3"><i class="fas fa-copy mr-2"></i>Salin ke Bahasa Lain</h5>
          @php $defLang = null; foreach($languages as $l) { if($l->is_default == 1) $defLang = $l; } @endphp
          @if($defLang) @foreach ($languages as $language) @continue($language->id == $defLang->id)
            <div class="form-check py-1"><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="cloneLang{{ $language->id }}" onchange="cloneInput('collapse{{ $defLang->id }}', 'collapse{{ $language->id }}', event)"><label class="custom-control-label" for="cloneLang{{ $language->id }}">Bahasa {{ $language->name }}</label></div></div>
          @endforeach @endif
        </div></div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="GoogleMapModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary text-white"><h5 class="modal-title font-weight-bold"><i class="fas fa-map-marker-alt mr-2"></i>Titik Peta</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div>
        <div class="modal-body p-0"><div id="map" style="height: 500px; width: 100%;"></div></div>
        <div class="modal-footer bg-light"><button type="button" class="btn btn-success font-weight-bold" data-dismiss="modal">Terapkan</button></div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if ($settings->google_map_api_key_status == 1)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps_api_key') }}&libraries=places&callback=initMap" async defer></script>
    <script src="{{ asset('assets/admin/js/map-init2.js') }}"></script>
    <style>.pac-container { z-index: 100000 !important; }</style>
  @endif
  <script>
    var currentStep = 1;
    // Airbnb-style Toast Helper
    function showToast(message) {
        let container = document.getElementById('airbnb-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'airbnb-toast-container';
            container.className = 'airbnb-toast-container';
            container.innerHTML = '<div class="airbnb-toast"><i class="fas fa-exclamation-circle"></i><span id="airbnb-toast-message"></span></div>';
            document.body.appendChild(container);
        }
        
        document.getElementById('airbnb-toast-message').innerText = message;
        container.classList.add('active');
        
        setTimeout(() => {
            container.classList.remove('active');
        }, 4000);
    }

    function nextStep(step) {
        const currentStepEl = document.getElementById('step-' + currentStep);
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let errors = [];

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                let label = field.previousElementSibling ? field.previousElementSibling.innerText : 'Beberapa kolom';
                errors.push(label.replace('*', '').trim());
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (currentStep === 1) {
            const mainImage = document.querySelector('input[name="feature_image"]');
            if (mainImage && !mainImage.value) {
                document.querySelector('.photo-upload-placeholder').style.borderColor = '#ff5a5f';
                errors.push('Foto Lokasi');
            } else {
                document.querySelector('.photo-upload-placeholder').style.borderColor = '';
            }
        }

        if (errors.length > 0) {
            showToast('Harap lengkapi: ' + errors[0] + (errors.length > 1 ? ' dan ' + (errors.length - 1) + ' lainnya' : ''));
            return;
        }

        document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.wizard-steps li').forEach(el => el.classList.remove('active', 'completed'));
        
        currentStep = step;
        document.getElementById('step-' + currentStep).classList.add('active');
        
        for(let i=1; i<=4; i++) {
            let tab = document.getElementById('step-nav-' + i);
            if(tab) {
                if(i < step) tab.classList.add('completed');
                if(i == step) tab.classList.add('active');
            }
        }
        
        if(step == 3 && typeof map !== 'undefined') setTimeout(() => google.maps.event.trigger(map, 'resize'), 300);
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }
    function toggleAmenity(el, id) {
      el.classList.toggle('selected');
      let checkbox = document.getElementById('check-' + id);
      checkbox.checked = !checkbox.checked;
    }
    function previewImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          let container = input.closest('.photo-upload-placeholder');
          let preview = container.querySelector('.thumb-preview');
          preview.querySelector('img').src = e.target.result;
          preview.classList.remove('d-none');
          container.querySelector('i').classList.add('d-none');
          container.querySelector('p').classList.add('d-none');
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    function addFaq(langCode) {
      var container = document.getElementById('faq-container-' + langCode);
      var div = document.createElement('div');
      div.className = 'faq-item border p-3 mt-3 bg-white shadow-sm rounded';
      div.innerHTML = `<div class="form-group mb-2 p-0"><label class="font-weight-bold">Pertanyaan</label><input type="text" name="${langCode}_faq_q[]" class="form-control border-primary"></div><div class="form-group p-0 mb-3"><label class="font-weight-bold">Jawaban</label><textarea name="${langCode}_faq_a[]" class="form-control" rows="3"></textarea></div><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">Hapus</button>`;
      container.appendChild(div);
    }
  </script>
  <script src="{{ asset('assets/admin/js/admin-hotel.js') }}"></script>
  <script src="{{ asset('assets/admin/js/admin-dropzone.js') }}"></script>
@endsection
