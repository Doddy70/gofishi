@php
  $vendor     = Auth::guard('vendor')->user();
  $hasRooms   = isset($totalRooms) && $totalRooms > 0;
  $docsOk     = !empty($vendor->ktp_file)
             && !empty($vendor->boat_ownership_file)
             && !empty($vendor->driving_license_file)
             && !empty($vendor->self_photo_file);
  $profileOk  = !empty($vendor->email) && !empty($vendor->phone);
  $allDone    = $hasRooms && $docsOk && $profileOk;
  $doneCount  = (int)$hasRooms + (int)$docsOk + (int)$profileOk;
  $totalSteps = 3;
  $pct        = round(($doneCount / $totalSteps) * 100);
@endphp

@if (!$allDone)
<div class="ob-banner card mb-4" id="onboardingBanner">
  {{-- Header --}}
  <div class="ob-header d-flex align-items-center justify-content-between px-4 py-3">
    <div class="d-flex align-items-center">
      <span class="ob-emoji mr-3">🚢</span>
      <div>
        <h5 class="ob-title mb-0">{{ __('Selesaikan profil Host / Vendor Anda') }}</h5>
        <small class="ob-subtitle text-muted">
          {{ $doneCount }}/{{ $totalSteps }} {{ __('langkah selesai') }} — {{ $pct }}% {{ __('selesai') }}
        </small>
      </div>
    </div>
    <button type="button" class="btn btn-sm ob-dismiss-btn" id="dismissOnboarding"
            title="{{ __('Tutup') }}">
      <i class="fas fa-times"></i>
    </button>
  </div>

  {{-- Progress bar --}}
  <div class="ob-progress-wrap px-4">
    <div class="ob-progress-bar-bg">
      <div class="ob-progress-bar-fill" style="width: {{ $pct }}%"></div>
    </div>
  </div>

  {{-- Steps --}}
  <div class="ob-steps px-4 py-3">
    <div class="row">

      {{-- Step 1: Profile --}}
      <div class="col-md-4 mb-2 mb-md-0">
        <div class="ob-step {{ $profileOk ? 'ob-step--done' : 'ob-step--active' }}">
          <div class="ob-step-icon">
            @if ($profileOk)
              <i class="fas fa-check-circle"></i>
            @else
              <i class="fas fa-user-circle"></i>
            @endif
          </div>
          <div class="ob-step-body">
            <div class="ob-step-label">{{ __('Lengkapi Profil') }}</div>
            <div class="ob-step-desc">{{ __('Nama, email, dan nomor telepon') }}</div>
            @if (!$profileOk)
              <a href="{{ route('vendor.edit.profile') }}" class="ob-step-action">
                {{ __('Perbarui Profil') }} <i class="fas fa-arrow-right ml-1"></i>
              </a>
            @endif
          </div>
        </div>
      </div>

      {{-- Step 2: Upload Documents --}}
      <div class="col-md-4 mb-2 mb-md-0">
        <div class="ob-step {{ $docsOk ? 'ob-step--done' : ($profileOk ? 'ob-step--active' : 'ob-step--pending') }}">
          <div class="ob-step-icon">
            @if ($docsOk)
              <i class="fas fa-check-circle"></i>
            @else
              <i class="fas fa-file-upload"></i>
            @endif
          </div>
          <div class="ob-step-body">
            <div class="ob-step-label">{{ __('Upload Dokumen') }}</div>
            <div class="ob-step-desc">{{ __('KTP, SIM, kepemilikan kapal & foto diri') }}</div>
            @if (!$docsOk)
              <a href="{{ route('vendor.edit.profile') }}#documents" class="ob-step-action">
                {{ __('Upload Sekarang') }} <i class="fas fa-arrow-right ml-1"></i>
              </a>
            @endif
          </div>
        </div>
      </div>

      {{-- Step 3: Add Boat --}}
      <div class="col-md-4">
        <div class="ob-step {{ $hasRooms ? 'ob-step--done' : ($docsOk ? 'ob-step--active' : 'ob-step--pending') }}">
          <div class="ob-step-icon">
            @if ($hasRooms)
              <i class="fas fa-check-circle"></i>
            @else
              <i class="fas fa-ship"></i>
            @endif
          </div>
          <div class="ob-step-body">
            <div class="ob-step-label">{{ __('Daftarkan Perahu') }}</div>
            <div class="ob-step-desc">{{ __('Tambahkan perahu pertama Anda') }}</div>
            @if (!$hasRooms)
              <a href="{{ route('vendor.perahu_management.create_perahu', ['language' => $defaultLang->code]) }}"
                 class="ob-step-action">
                {{ __('Tambah Perahu') }} <i class="fas fa-arrow-right ml-1"></i>
              </a>
            @endif
          </div>
        </div>
      </div>

    </div>{{-- /row --}}
  </div>{{-- /ob-steps --}}
</div>{{-- /ob-banner --}}

<style>
/* ──────────────────────────────────────────────
   Onboarding Banner Styles
────────────────────────────────────────────── */
.ob-banner {
  border: none;
  border-radius: 14px;
  background: linear-gradient(135deg, #1a2035 0%, #1e2d4d 100%);
  box-shadow: 0 8px 32px rgba(30, 45, 77, 0.25);
  overflow: hidden;
  animation: ob-slideDown 0.4s cubic-bezier(.22,.68,0,1.2) both;
}
@keyframes ob-slideDown {
  from { opacity: 0; transform: translateY(-18px); }
  to   { opacity: 1; transform: translateY(0); }
}
.ob-header { border-bottom: 1px solid rgba(255,255,255,.08); }
.ob-emoji  { font-size: 2rem; line-height:1; }
.ob-title  { color: #fff; font-size: 1rem; font-weight: 700; }
.ob-subtitle { color: rgba(255,255,255,.55); font-size: .78rem; }
.ob-dismiss-btn {
  background: rgba(255,255,255,.1);
  color: rgba(255,255,255,.6);
  border: none;
  border-radius: 8px;
  width: 32px; height: 32px;
  padding: 0;
  transition: background .2s;
}
.ob-dismiss-btn:hover { background: rgba(255,255,255,.2); color:#fff; }

/* Progress bar */
.ob-progress-wrap { padding-bottom: 4px; }
.ob-progress-bar-bg {
  height: 4px;
  background: rgba(255,255,255,.12);
  border-radius: 99px;
  overflow: hidden;
}
.ob-progress-bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #4fc3f7, #29b6f6);
  border-radius: 99px;
  transition: width .6s ease;
}

/* Steps */
.ob-step {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px 14px;
  border-radius: 10px;
  background: rgba(255,255,255,.05);
  height: 100%;
  transition: background .2s;
}
.ob-step:hover { background: rgba(255,255,255,.09); }
.ob-step-icon {
  font-size: 1.4rem;
  line-height: 1;
  padding-top: 2px;
  flex-shrink: 0;
}
.ob-step--done .ob-step-icon  { color: #4ade80; }
.ob-step--active .ob-step-icon { color: #60a5fa; }
.ob-step--pending .ob-step-icon { color: rgba(255,255,255,.25); }

.ob-step-label {
  font-size: .82rem;
  font-weight: 700;
  color: #fff;
}
.ob-step--done .ob-step-label  { color: #4ade80; }
.ob-step--pending .ob-step-label { color: rgba(255,255,255,.4); }

.ob-step-desc {
  font-size: .74rem;
  color: rgba(255,255,255,.5);
  margin-top: 2px;
}
.ob-step-action {
  display: inline-block;
  margin-top: 6px;
  font-size: .74rem;
  font-weight: 600;
  color: #60a5fa;
  text-decoration: none;
  transition: color .2s;
}
.ob-step-action:hover { color: #93c5fd; text-decoration: none; }
</style>

<script>
(function(){
  var btn = document.getElementById('dismissOnboarding');
  var banner = document.getElementById('onboardingBanner');
  if(btn && banner){
    btn.addEventListener('click', function(){
      banner.style.transition = 'opacity .3s, transform .3s';
      banner.style.opacity = '0';
      banner.style.transform = 'translateY(-12px)';
      setTimeout(function(){ banner.style.display = 'none'; }, 300);
      // Remember dismiss in localStorage
      try { localStorage.setItem('ob_dismissed_{{ $vendor->id }}', '1'); } catch(e){}
    });
    // Auto-hide if already dismissed
    try {
      if(localStorage.getItem('ob_dismissed_{{ $vendor->id }}') === '1'){
        banner.style.display = 'none';
      }
    } catch(e){}
  }
})();
</script>
@endif
