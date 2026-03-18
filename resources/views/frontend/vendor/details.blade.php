@extends('frontend.layout')

@section('pageHeading')
  {{ $vendor->username }} - Certified Fishing Captain
@endsection

@section('customStyle')
<style>
    :root {
        --airbnb-rose: #E31C5F;
        --fishing-blue: #0052cc;
    }
    .captain-header { padding: 40px 0; background: #f7f7f7; border-bottom: 1px solid #ddd; }
    .captain-card { border-radius: 24px; box-shadow: 0 6px 16px rgba(0,0,0,0.12); padding: 24px; background: white; }
    .captain-img-vip { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .badge-certified { background: var(--fishing-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .big-catch-gallery img { border-radius: 12px; height: 200px; object-fit: cover; width: 100%; transition: transform 0.3s; }
    .big-catch-gallery img:hover { transform: scale(1.03); }
    .spec-tag { background: #eee; padding: 4px 12px; border-radius: 15px; display: inline-block; margin: 2px; font-size: 13px; }
    .section-title { font-weight: 800; font-size: 24px; margin-bottom: 24px; }
</style>
@endsection

@section('content')
<div class="captain-header header-next">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4">
                    <div class="position-relative">
                        @if ($vendor_id == 0)
                            <img class="captain-img-vip" src="{{ asset('assets/img/admins/' . $vendor->image) }}" alt="Captain">
                        @else
                            <img class="captain-img-vip" src="{{ $vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/img/blank-user.jpg') }}" alt="Captain">
                        @endif
                        @if(@$vendorInfo->license_number)
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                                <i class="fas fa-check-circle text-primary" style="font-size: 24px;"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="mb-1 fw-bold" style="font-size: 32px;">{{ $vendor->username }}</h1>
                        <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                            @if(@$vendorInfo->license_number)
                                <span class="badge-certified"><i class="fas fa-ship me-1"></i> U.S. Coast Guard Licensed</span>
                            @endif
                            <span class="text-muted"><i class="fal fa-calendar-alt me-1"></i> Member since {{ \Carbon\Carbon::parse($vendor->created_at)->format('Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <button class="btn btn-lg btn-outline-dark radius-md px-4" data-bs-toggle="modal" data-bs-target="#contactModal">
                    <i class="fal fa-envelope me-2"></i> {{ __('Tanya Kapten') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row gx-lg-5">
        {{-- Left Sidebar: Info & Stats --}}
        <div class="col-lg-4">
            <div class="captain-card mb-4">
                <h5 class="fw-bold mb-3">{{ __('Tentang Kapten') }}</h5>
                <p class="text-muted" style="line-height: 1.6;">
                    {{ @$vendorInfo->details ?: __('Belum ada informasi bio untuk kapten ini.') }}
                </p>
                <hr>
                <div class="mb-3">
                    <h6 class="fw-bold small text-uppercase text-muted">{{ __('Spesialisasi Teknik') }}</h6>
                    <div class="mt-2">
                        @if(@$vendorInfo->specializations)
                            @foreach(explode(',', $vendorInfo->specializations) as $spec)
                                <span class="spec-tag">{{ trim($spec) }}</span>
                            @endforeach
                        @else
                            <span class="text-muted small">General Saltwater Fishing</span>
                        @endif
                    </div>
                </div>
                <div class="mb-0">
                    <h6 class="fw-bold small text-uppercase text-muted">{{ __('Lokasi Operasi') }}</h6>
                    <p class="mb-0 mt-1"><i class="fal fa-map-marker-alt text-primary me-2"></i> {{ @$vendorInfo->city ?: 'Jakarta Utara' }}, {{ @$vendorInfo->country }}</p>
                </div>
            </div>

            {{-- Verified Badge Box --}}
            @if(@$vendorInfo->license_number)
            <div class="alert alert-info border-0 radius-md p-4">
                <div class="d-flex gap-3">
                    <i class="fas fa-shield-check text-info" style="font-size: 24px;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Identitas Terverifikasi</h6>
                        <p class="small mb-0">Kapten ini telah memverifikasi lisensi profesional dan identitas mereka dengan Go Fishi.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Content: Big Catch & Listings --}}
        <div class="col-lg-8">
            
            {{-- Big Catch Gallery --}}
            <section class="mb-5">
                <h3 class="section-title"><i class="fas fa-trophy text-warning me-2"></i> {{ __('Galeri "Big Catch"') }}</h3>
                <div class="row big-catch-gallery g-3">
                    @if(count($captain_galleries) > 0)
                        @foreach($captain_galleries as $gallery)
                            <div class="col-md-4 col-6">
                                <a href="{{ asset('assets/img/captain/gallery/' . $gallery->image) }}" class="lightbox-single">
                                    <img src="{{ asset('assets/img/captain/gallery/' . $gallery->image) }}" alt="{{ $gallery->title }}">
                                    @if($gallery->title || $gallery->weight)
                                        <div class="mt-2">
                                            <span class="fw-bold d-block small">{{ $gallery->title }}</span>
                                            <span class="text-muted" style="font-size: 11px;">{{ $gallery->weight }}</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="p-5 text-center bg-light radius-md">
                                <i class="fal fa-camera-retro mb-3 d-block" style="font-size: 40px; color: #ccc;"></i>
                                <p class="text-muted">Kapten belum mengunggah foto hasil tangkapan.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Charter Listings --}}
            <section>
                <h3 class="section-title">{{ __('Charter Perahu dari Kapten Ini') }}</h3>
                <div class="row">
                    @if (count($hotel_contents) > 0)
                        @foreach ($hotel_contents as $hotel_content)
                            <div class="col-md-6">
                                {{-- Kita gunakan partial card yang sudah ada namun disesuaikan --}}
                                <div class="card border-0 shadow-sm radius-md mb-4 overflow-hidden">
                                    <img src="{{ asset('assets/img/hotel/logo/' . $hotel_content->logo) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-1">{{ $hotel_content->title }}</h5>
                                        <p class="small text-muted mb-3"><i class="fal fa-map-marker-alt me-1"></i> {{ $hotel_content->address }}</p>
                                        <a href="{{ route('frontend.lokasi.details', ['slug' => $hotel_content->slug, 'id' => $hotel_content->id]) }}" class="btn btn-dark btn-sm w-100 radius-md">Lihat Paket Charter</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Kapten ini belum memiliki daftar charter aktif.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>

{{-- Modal Contact --}}
@include('frontend.vendor.contact-modal')

@endsection
