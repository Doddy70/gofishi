@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Additional Services') }}</h4>
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
        <a href="#">{{ __('Kelola Perahu') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Additional Services') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Additional Services') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.perahu_management.perahu', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-3">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="alert alert-danger pb-1 dis-none" id="commonFormErrors">
                <ul></ul>
              </div>

              <form id="commonForm" action="{{ route('vendor.perahu_management.update_additional_service', $room->id) }}"
                method="POST">
                @csrf
                <div class="row">
                  @php
                    $hasservice = json_decode($room->additional_service, true);
                  @endphp
                  @foreach ($services as $service)
                    <div class="col-lg-6 col-md-6">
                      <ul class="list-group list-group-style-2">
                        <li class="list-group-item d-flex gap-10 justify-content-between align-items-center mb-10">
                          <div class="d-flex gap-10">
                            <input class="input-checkbox" type="checkbox" name="checkbox[]"
                              @if ($hasservice && array_key_exists($service->id, $hasservice)) checked @endif id="checkbox_{{ $service->id }}"
                              value="{{ $service->id }}">
                            <label for="checkbox_{{ $service->id }}"><span>{{ $service->title }}</span></label>
                          </div>
                          <div class="input-field d-flex gap-10 align-items-center">
                            <input class="form-control" name="price_{{ $service->id }}" type="text"
                              value="{{ ($hasservice && array_key_exists($service->id, $hasservice)) ? $hasservice[$service->id] : '' }}">
                            <span class="">({{ $settings->base_currency_text }})</span>
                          </div>
                        </li>
                      </ul>
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
              <button type="submit" form="commonForm" class="btn btn-primary" id="submitBtn">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
