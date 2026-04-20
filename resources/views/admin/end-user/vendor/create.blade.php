@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Vendor') }}</h4>
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
        <a href="#">{{ __('Vendors Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Vendor') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Add Vendor') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <div class="alert alert-danger pb-1 dis-none" id="commonFormErrors">
                <ul></ul>
              </div>
              <form id="commonForm" action="{{ route('admin.vendor_management.save-vendor') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      </div>
                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>


                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Username') . '*' }}</label>
                      <input type="text" value="" class="form-control" name="username"
                        placeholder="{{ __('Enter Username') }}">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Password') . '*' }}</label>
                      <input type="password" value="" class="form-control" name="password"
                        placeholder="{{ __('Enter Password') }} ">
                      <p id="editErr_password" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Email') . '*' }}</label>
                      <input type="email" value="" class="form-control" name="email"
                        placeholder="{{ __('Enter Email') }}">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Phone') . '*' }}</label>
                      <input type="tel" value="" class="form-control" name="phone"
                        placeholder="{{ __('Enter Phone') }}">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Date of Birth') . '*' }}</label>
                      <input type="date" value="" class="form-control" name="dob">
                      <p id="editErr_dob" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                </div>

                <div class="row mt-4">
                  <div class="col-lg-12">
                     <h4 class="mb-3"><strong>{{ __('Legal Documents') }}</strong></h4>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('KTP / ID Card') . '*' }}</label>
                      <input type="file" class="form-control" name="ktp_file">
                      <p id="editErr_ktp_file" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Boat Ownership Proof') . '*' }}</label>
                      <input type="file" class="form-control" name="boat_ownership_file">
                      <p id="editErr_boat_ownership_file" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Captain Driving License') . '*' }}</label>
                      <input type="file" class="form-control" name="driving_license_file">
                      <p id="editErr_driving_license_file" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Self Photo') . '*' }}</label>
                      <input type="file" class="form-control" name="self_photo_file">
                      <p id="editErr_self_photo_file" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                <div id="accordion" class="mt-5">
                  @foreach ($languages as $lang)
                    <div class="version">
                      <div class="version-header" id="heading{{ $lang->id }}">
                        <h5 class="mb-0">
                          <button type="button"
                            class="btn btn-link {{ $lang->direction == 1 ? 'rtl text-right' : '' }}"
                            data-toggle="collapse" data-target="#collapse{{ $lang->id }}"
                            aria-expanded="{{ $lang->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $lang->id }}">
                            {{ $lang->name . __(' Language Profile') }} {{ $lang->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $lang->id }}"
                        class="collapse {{ $lang->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $lang->id }}" data-parent="#accordion">
                        <div class="version-body {{ $lang->direction == 1 ? 'rtl text-right' : '' }}">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label>{{ __('Host / Display Name') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $lang->code }}_name" placeholder="{{ __('Enter Host Name') }}">
                                <p id="editErr_{{ $lang->code }}_name" class="mt-1 mb-0 text-danger em"></p>
                              </div>
                            </div>
                            <div class="d-none">
                              <input type="hidden" name="{{ $lang->code }}_country" value="">
                              <input type="hidden" name="{{ $lang->code }}_city" value="">
                              <input type="hidden" name="{{ $lang->code }}_state" value="">
                              <input type="hidden" name="{{ $lang->code }}_zip_code" value="">
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label>{{ __('Address') }}</label>
                                <textarea name="{{ $lang->code }}_address" class="form-control" placeholder="{{ __('Enter Address') }}"></textarea>
                              </div>
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label>{{ __('Bio / Details') }}</label>
                                <textarea name="{{ $lang->code }}_details" class="form-control" rows="4" placeholder="{{ __('Enter Bio/Details') }}"></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
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
  </div>
@endsection
