@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Manage Packages for') }}: {{ $perahu->nama_km }}</h4>
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
        <a href="{{ route('vendor.perahu_management.edit_perahu', ['id' => $perahu->id]) }}">{{ $perahu->nama_km }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Packages') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-6">
                <div class="card-title d-inline-block">{{ __('Boat Packages') }}</div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
              <a href="{{ route('vendor.perahu.packages.create', ['perahu_id' => $perahu->id]) }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Package') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($packages) == 0)
                <h3 class="text-center">{{ __('NO PACKAGES FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Duration') }}</th>
                        <th scope="col">{{ __('Area') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($packages as $package)
                        <tr>
                          <td>{{ $package->name }}</td>
                          <td>{{ $package->price }}</td>
                          <td>{{ $package->duration_days }} {{ __('Day(s)') }}</td>
                          <td>{{ $package->area }}</td>
                          <td>
                            @if ($package->status == 1)
                              <span class="badge badge-success">{{ __('Active') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Deactive') }}</span>
                            @endif
                          </td>
                          <td>
                            <a href="{{ route('vendor.perahu.packages.edit', ['perahu_id' => $perahu->id, 'package' => $package->id]) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                            <form class="deleteForm d-inline-block" action="{{ route('vendor.perahu.packages.destroy', ['perahu_id' => $perahu->id, 'package' => $package->id]) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                {{ __('Delete') }}
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
