@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
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
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Profile') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <div class="alert alert-danger pb-1 dis-none" id="commonFormErrors">
                <ul></ul>
              </div>
              <form id="commonForm" action="{{ route('vendor.update_profile') }}" method="post">
                @csrf
                <h2>{{ __('Details') }}</h2>
                <hr>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($vendor->photo != null)
                          <img src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                        <p class="mt-2 mb-0 text-warning">{{ __('Image Size 80x80') }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username') . '*' }}</label>
                      <input type="text" value="{{ $vendor->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email') . '*' }}</label>
                      <input type="text" value="{{ $vendor->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $vendor->phone }}" class="form-control" name="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('WhatsApp Number') }}</label>
                      <input type="tel" value="{{ $vendor->whatsapp_number }}" class="form-control" name="whatsapp_number" placeholder="62812345678">
                      <p id="editErr_whatsapp_number" class="mt-1 mb-0 text-danger em"></p>
                      <small class="text-muted">{{ __('Gunakan format internasional tanpa + atau 0 (Contoh: 62812345678)') }}</small>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_email_addresss == 1 ? 'checked' : '' }}
                              name="show_email_addresss" class="custom-control-input" id="show_email_addresss">
                            <label class="custom-control-label"
                              for="show_email_addresss">{{ __('Show Email Address in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_phone_number == 1 ? 'checked' : '' }}
                              name="show_phone_number" class="custom-control-input" id="show_phone_number">
                            <label class="custom-control-label"
                              for="show_phone_number">{{ __('Show Phone Number in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->whatsapp_status == 1 ? 'checked' : '' }}
                              name="whatsapp_status" class="custom-control-input" id="whatsapp_status">
                            <label class="custom-control-label"
                              for="whatsapp_status">{{ __('Show WhatsApp Chat Button') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_contact_form == 1 ? 'checked' : '' }}
                              name="show_contact_form" class="custom-control-input" id="show_contact_form">
                            <label class="custom-control-label"
                              for="show_contact_form">{{ __('Show Contact Form') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12 mt-5" id="documents">
                    <h2>{{ __('Dokumen Pendukung') }}</h2>
                    <hr>

                    {{-- Document Verification Status --}}
                    @if ($vendor->document_verified == 1)
                      <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ __('Selamat! Dokumen Anda telah diverifikasi.') }}
                      </div>
                    @elseif ($vendor->document_verified == 2)
                      <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i> {{ __('Dokumen Anda ditolak.') }}
                        @if ($vendor->rejection_reason)
                          <p class="mb-0"><strong>{{ __('Alasan') }}:</strong> {{ $vendor->rejection_reason }}</p>
                        @endif
                        <p class="mb-0 mt-2">{{ __('Silakan unggah kembali dokumen yang sesuai.') }}</p>
                      </div>
                    @elseif ($vendor->document_verified == 0 && ($vendor->ktp_file || $vendor->boat_ownership_file))
                      <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> {{ __('Dokumen Anda sedang dalam peninjauan oleh tim kami. Mohon tunggu.') }}
                      </div>
                    @else
                       <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> {{ __('Silakan lengkapi dokumen Anda untuk dapat mulai mendaftarkan perahu.') }}
                      </div>
                    @endif

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>{{ __('KTP') }}</label>
                          @if ($vendor->ktp_file)
                            <div class="mb-2">
                              <a href="{{ asset('assets/admin/img/vendor-document/' . $vendor->ktp_file) }}" target="_blank" class="btn btn-xs btn-info">
                                <i class="fas fa-eye"></i> {{ __('Lihat KTP') }}
                              </a>
                            </div>
                          @endif
                          <input type="file" class="form-control" name="ktp_file" accept="image/*,.pdf" @if($vendor->document_verified == 1) disabled @endif>
                          <p id="editErr_ktp_file" class="mt-1 mb-0 text-danger em"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>{{ __('Bukti Kepemilikan Kapal') }}</label>
                          @if ($vendor->boat_ownership_file)
                            <div class="mb-2">
                              <a href="{{ asset('assets/admin/img/vendor-document/' . $vendor->boat_ownership_file) }}" target="_blank" class="btn btn-xs btn-info">
                                <i class="fas fa-eye"></i> {{ __('Lihat Bukti') }}
                              </a>
                            </div>
                          @endif
                          <input type="file" class="form-control" name="boat_ownership_file" accept="image/*,.pdf" @if($vendor->document_verified == 1) disabled @endif>
                          <p id="editErr_boat_ownership_file" class="mt-1 mb-0 text-danger em"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>{{ __('Izin Mengemudi Kapal (SIM)') }}</label>
                          @if ($vendor->driving_license_file)
                            <div class="mb-2">
                              <a href="{{ asset('assets/admin/img/vendor-document/' . $vendor->driving_license_file) }}" target="_blank" class="btn btn-xs btn-info">
                                <i class="fas fa-eye"></i> {{ __('Lihat SIM') }}
                              </a>
                            </div>
                          @endif
                          <input type="file" class="form-control" name="driving_license_file" accept="image/*,.pdf" @if($vendor->document_verified == 1) disabled @endif>
                          <p id="editErr_driving_license_file" class="mt-1 mb-0 text-danger em"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>{{ __('Foto Diri') }}</label>
                          @if ($vendor->self_photo_file)
                            <div class="mb-2">
                              <a href="{{ asset('assets/admin/img/vendor-document/' . $vendor->self_photo_file) }}" target="_blank" class="btn btn-xs btn-info">
                                <i class="fas fa-eye"></i> {{ __('Lihat Foto') }}
                              </a>
                            </div>
                          @endif
                          <input type="file" class="form-control" name="self_photo_file" accept="image/*" @if($vendor->document_verified == 1) disabled @endif>
                          <p id="editErr_self_photo_file" class="mt-1 mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="commonForm" class="btn btn-success">
                {{ __('Update') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
