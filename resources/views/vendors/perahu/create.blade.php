@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Perahu') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}"><i class="flaticon-home"></i></a>
      </li>
      <li class="separator"><i class="flaticon-right-arrow"></i></li>
      <li class="nav-item"><a href="#">{{ __('Perahu Management') }}</a></li>
      <li class="separator"><i class="flaticon-right-arrow"></i></li>
      <li class="nav-item"><a href="#">{{ __('Add Perahu') }}</a></li>
    </ul>
  </div>

  @php
    $vendorId = Auth::guard('vendor')->user()->id;
    $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId);
  @endphp

  {{-- ======= WIZARD STEP INDICATOR ======= --}}
  <div class="wizard-container mb-4">
    <ul class="wizard-steps">
      <li class="active" id="step-1-tab">
        <div class="step-num">1</div>
        <div class="step-text">Media & Identitas</div>
      </li>
      <li id="step-2-tab">
        <div class="step-num">2</div>
        <div class="step-text">Spesifikasi</div>
      </li>
      <li id="step-3-tab">
        <div class="step-num">3</div>
        <div class="step-text">Harga & Rute</div>
      </li>
      <li id="step-4-tab">
        <div class="step-num">4</div>
        <div class="step-text">Booking & Simpan</div>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div id="roomErrors" class="alert alert-danger pb-1 dis-none"><ul></ul></div>

      <form id="roomForm" action="{{ route('vendor.perahu_management.store_perahu') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="sliders"></div>

        {{-- ======= STEP 1: MEDIA & IDENTITAS ======= --}}
        <div class="wizard-step active" id="step-1">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
               <h4 class="card-title fw-bold text-primary"><i class="fas fa-camera mr-2"></i>Langkah 1: Media & Identitas Kapal</h4>
            </div>
            <div class="card-body">
              <div class="row">
                {{-- Featured Image Airbnb Style --}}
                <div class="col-lg-12 mb-4">
                  <label class="font-weight-bold d-block mb-3 text-dark">Tambahkan foto utama perahu Anda <span class="text-danger">*</span></label>
                  <div class="photo-upload-placeholder" onclick="document.querySelector('.img-input').click()">
                    <i class="fas fa-camera"></i>
                    <p class="font-weight-bold text-dark mb-1">Klik untuk mengunggah foto utama</p>
                    <p class="text-muted small">Disarankan landscape dengan resolusi tinggi</p>
                    <div class="thumb-preview border rounded d-none mt-2" style="max-width: 300px;">
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img" style="max-width: 100%;">
                    </div>
                    <input type="file" class="img-input d-none" name="feature_image" onchange="previewImage(this)">
                  </div>
                </div>

                {{-- Gallery --}}
                <div class="col-lg-12 mt-4">
                  <label class="font-weight-bold mb-3 text-dark">Galeri Foto Kapal (Minimal 5 Foto) <span class="text-danger">*</span></label>
                  <div id="dropzone-api-url" data-url="{{ route('vendor.perahu_management.perahu.imagesstore') }}"></div>
                  <div class="dropzone create border-primary mb-3" id="my-dropzone">
                     <div class="fallback"><input name="file" type="file" multiple /></div>
                  </div>
                </div>

                <div class="col-lg-12 mt-4 pt-4 border-top">
                    <div class="form-group p-0">
                      <label class="font-weight-bold text-dark">{{ __('Beri nama untuk Kapal Perahu Anda') . '*' }}</label>
                      <input type="text" class="form-control form-control-lg border-primary" name="nama_km" placeholder="Contoh: KM Pesona Laut" required>
                      <p class="text-muted small mt-1">Nama ini akan dilihat oleh customer saat melakukan pencarian.</p>
                    </div>
                </div>
              </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-end">
               <button type="button" class="btn btn-primary px-5 py-3 font-weight-bold rounded-pill" onclick="nextStep(2)">Lanjut ke Spesifikasi <i class="fas fa-arrow-right ml-2"></i></button>
            </div>
          </div>
        </div>

        {{-- (Steps 2 & 3 remain mostly same for data integrity, focusing on Step 4's UI) --}}
        
        {{-- ======= STEP 2: SPESIFIKASI ======= --}}
        <div class="wizard-step" id="step-2">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
               <h4 class="card-title fw-bold text-primary"><i class="fas fa-microchip mr-2"></i>Langkah 2: Spesifikasi Kapal</h4>
            </div>
            <div class="card-body">
              <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group"><label>Nama Kapten</label><input type="text" class="form-control" name="captain_name" placeholder="Nama Kapten"></div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group"><label>Jumlah Kamar</label><input type="number" class="form-control" name="bedroom_count" value="0"></div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group"><label>Jumlah Toilet</label><input type="number" class="form-control" name="toilet_count" value="0"></div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group"><label>Mesin 1</label><input type="text" class="form-control" name="engine_1" placeholder="Misal: Yamaha 250HP"></div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group"><label>Mesin 2</label><input type="text" class="form-control" name="engine_2" placeholder="Misal: Honda 250HP"></div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group"><label>Panjang (m)</label><input type="text" class="form-control" name="boat_length" placeholder="0"></div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group"><label>Lebar (m)</label><input type="text" class="form-control" name="boat_width" placeholder="0"></div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group"><label>Kapasitas (Orang)</label><input type="number" class="form-control" name="adult" placeholder="0"></div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group"><label>Jumlah Kru</label><input type="number" class="form-control" name="crew_count" placeholder="0"></div>
                  </div>
              </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
               <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill" onclick="nextStep(1)"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
               <button type="button" class="btn btn-primary px-5 py-3 font-weight-bold rounded-pill" onclick="nextStep(3)">Lanjut ke Harga <i class="fas fa-arrow-right ml-2"></i></button>
            </div>
          </div>
        </div>

        {{-- ======= STEP 3: HARGA & RUTE ======= --}}
        <div class="wizard-step" id="step-3">
           <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
               <h4 class="card-title fw-bold text-primary"><i class="fas fa-tags mr-2"></i>Langkah 3: Alur Sewa & Harga</h4>
            </div>
            <div class="card-body">
                @for ($i = 1; $i <= 3; $i++)
                  <div class="package-box p-4 mb-4 border-0 rounded-lg shadow-sm" style="background-color: #fbfcfe; border-radius: 16px !important;">
                    <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-anchor mr-2 text-primary"></i>Paket {{ $i }} Hari</h5>
                    <div class="row">
                       <div class="col-lg-3"><div class="form-group"><label>Harga (Rp)</label><input type="number" class="form-control border-primary" name="price_day_{{ $i }}" placeholder="0"></div></div>
                       <div class="col-lg-3"><div class="form-group"><label>Jam Kumpul</label><input type="time" class="form-control border-primary" name="meet_time_day_{{ $i }}"></div></div>
                       <div class="col-lg-3"><div class="form-group"><label>Jam Kembali</label><input type="time" class="form-control border-primary" name="return_time_day_{{ $i }}"></div></div>
                       <div class="col-lg-3"><div class="form-group"><label>Area Rute</label><input type="text" class="form-control border-primary" name="area_day_{{ $i }}" placeholder="Contoh: P. Matahari"></div></div>
                    </div>
                  </div>
                @endfor
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
               <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill" onclick="nextStep(2)"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
               <button type="button" class="btn btn-primary px-5 py-3 font-weight-bold rounded-pill" onclick="nextStep(4)">Lanjut ke Fasilitas <i class="fas fa-arrow-right ml-2"></i></button>
            </div>
          </div>
        </div>

        {{-- ======= STEP 4: FASILITAS & PENGATURAN ======= --}}
        <div class="wizard-step" id="step-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
               <h4 class="card-title fw-bold text-primary"><i class="fas fa-swimmer mr-2"></i>Langkah Terakhir: Fasilitas & Pembayaran</h4>
            </div>
            <div class="card-body">
              {{-- Amenities Grid AIRBNB STYLE --}}
              <div class="mb-5">
                <label class="font-weight-bold text-dark d-block mb-3 h5">Fasilitas apa saja yang tersedia di kapal ini?</label>
                <div class="amenity-grid">
                  @php
                    $amenities = [
                      ['name' => 'Wifi', 'icon' => 'fa-wifi', 'id' => 'wifi'],
                      ['name' => 'Toilet', 'icon' => 'fa-restroom', 'id' => 'toilet'],
                      ['name' => 'Kamar Tidur', 'icon' => 'fa-bed', 'id' => 'bed'],
                      ['name' => 'Dapur', 'icon' => 'fa-utensils', 'id' => 'kitchen'],
                      ['name' => 'Alat Pancing', 'icon' => 'fa-fish', 'id' => 'fishing'],
                      ['name' => 'Sound System', 'icon' => 'fa-music', 'id' => 'sound'],
                      ['name' => 'AC', 'icon' => 'fa-wind', 'id' => 'ac'],
                      ['name' => 'Sundeck', 'icon' => 'fa-sun', 'id' => 'sun'],
                    ];
                  @endphp
                  @foreach($amenities as $item)
                    <div class="amenity-card" onclick="toggleAmenity(this, '{{ $item['id'] }}')">
                      <i class="fas {{ $item['icon'] }}"></i>
                      <span>{{ $item['name'] }}</span>
                      <input type="checkbox" name="perahu_amenities[]" value="{{ $item['id'] }}" id="check-{{ $item['id'] }}">
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="row pt-4 border-top">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="font-weight-bold">Tipe Booking <i class="fas fa-info-circle ml-1 text-muted" title="Pilih bagaimana pelanggan melakukan pesanan"></i></label>
                    <select name="booking_type" class="form-control border-primary">
                      <option value="direct">{{ __('Real-Time (Langsung)') }}</option>
                      <option value="approval">{{ __('Persetujuan Host (Approval)') }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="font-weight-bold">Tipe DP (Down Payment)</label>
                    <select name="deposit_type" class="form-control border-primary">
                      <option value="full">{{ __('Bayar Lunas') }}</option>
                      <option value="percentage">{{ __('Persentase (%)') }}</option>
                      <option value="fixed">{{ __('Nilai Tetap (Rp)') }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="font-weight-bold">Nilai DP</label>
                    <input type="number" class="form-control border-primary" name="deposit_amount" value="0">
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
               <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill" onclick="nextStep(3)"><i class="fas fa-arrow-left mr-2"></i> Kembali</button>
               <button type="submit" class="btn btn-success px-5 py-3 font-weight-bold rounded-pill shadow"><i class="fas fa-check-circle mr-2"></i> Publikasikan Kapal Perahu</button>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
@endsection

@section('script')
  <script>
    "use strict";
    var storeUrl = "{{ route('vendor.perahu_management.perahu.imagesstore') }}";
    var removeUrl = "{{ route('vendor.perahu_management.perahu.imagermv') }}";
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
            const hasPreview = !document.querySelector('.thumb-preview').classList.contains('d-none');
            if (mainImage && !mainImage.value && !hasPreview) {
                document.querySelector('.photo-upload-placeholder').style.borderColor = '#ff5a5f';
                errors.push('Foto Utama');
            } else {
                document.querySelector('.photo-upload-placeholder').style.borderColor = '';
            }
        }

        if (errors.length > 0) {
            showToast('Harap lengkapi: ' + errors[0] + (errors.length > 1 ? ' dan ' + (errors.length - 1) + ' lainnya' : ''));
            return;
        }

        // Proceed to next step
        document.getElementById('step-' + currentStep).classList.remove('active');
        currentStep = step;
        document.getElementById('step-' + currentStep).classList.add('active');
        
        // Update Step Indicators
        for(let i=1; i<=4; i++) {
            let tab = document.getElementById('step-nav-' + i);
            if (tab) {
                tab.classList.remove('active', 'completed');
                if (i < step) tab.classList.add('completed');
                if (i === step) tab.classList.add('active');
            }
        }

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
          let img = preview.querySelector('img');
          img.src = e.target.result;
          preview.classList.remove('d-none');
          container.querySelector('i').classList.add('d-none');
          container.querySelector('p').classList.add('d-none');
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
  <script src="{{ asset('assets/js/dropzone-create.js') }}"></script>
@endsection
