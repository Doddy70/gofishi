@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Boat Package') }}</h4>
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
        <a href="{{ route('vendor.perahu_management.perahu') }}">{{ __('Perahu Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
       <li class="nav-item">
        <a href="{{ route('vendor.perahu.packages.index', ['perahu_id' => $perahu->id]) }}">{{ __('Packages for') }} {{ $perahu->nama_km }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Package') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Edit Package') }}</div>
        </div>

        <div class="card-body">
          <form id="ajaxForm" action="{{ route('vendor.perahu.packages.update', ['perahu_id' => $perahu->id, 'package' => $package->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">{{ __('Package Name') }}*</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{ $package->name }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="price">{{ __('Price') }}*</label>
                  <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ $package->price }}" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="duration_days">{{ __('Duration (Days)') }}*</label>
                  <input type="number" id="duration_days" name="duration_days" class="form-control" value="{{ $package->duration_days }}" min="1" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="area">{{ __('Fishing Area') }}</label>
                    <input type="text" id="area" name="area" class="form-control" value="{{ $package->area }}">
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meeting_time">{{ __('Meeting Time') }}</label>
                        <input type="time" id="meeting_time" name="meeting_time" class="form-control" value="{{ $package->meeting_time }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="return_time">{{ __('Return Time') }}</label>
                        <input type="time" id="return_time" name="return_time" class="form-control" value="{{ $package->return_time }}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
              <label for="description">{{ __('Description') }}</label>
              <textarea id="description" name="description" class="form-control" rows="3">{{ $package->description }}</textarea>
            </div>

            <div class="form-group">
              <label for="status">{{ __('Status') }}</label>
              <select id="status" name="status" class="form-control">
                <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
              </select>
            </div>
          </form>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="ajaxForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
