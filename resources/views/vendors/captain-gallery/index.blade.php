@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Galeri "Big Catch"') }}</h4>
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
        <a href="#">{{ __('Galeri "Big Catch"') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Foto Tangkapan Terbaik Anda') }}</div>
            </div>
            <div class="col-lg-8 text-right">
              <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('Tambah Foto') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($galleries) == 0)
                <h3 class="text-center">{{ __('BELUM ADA FOTO TANGKAPAN') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Gambar') }}</th>
                        <th scope="col">{{ __('Spesies Ikan') }}</th>
                        <th scope="col">{{ __('Berat') }}</th>
                        <th scope="col">{{ __('Urutan') }}</th>
                        <th scope="col">{{ __('Aksi') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($galleries as $gallery)
                        <tr>
                          <td>
                            <img src="{{ asset('assets/img/captain/gallery/' . $gallery->image) }}" alt="image" width="80">
                          </td>
                          <td>{{ $gallery->title }}</td>
                          <td>{{ $gallery->weight }}</td>
                          <td>{{ $gallery->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm editBtn mr-1" href="#" data-toggle="modal" data-target="#editModal" data-id="{{ $gallery->id }}" data-title="{{ $gallery->title }}" data-weight="{{ $gallery->weight }}" data-serial_number="{{ $gallery->serial_number }}" data-image="{{ asset('assets/img/captain/gallery/' . $gallery->image) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('vendor.captain_gallery.delete', $gallery->id) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
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

  {{-- Create Modal --}}
  @include('vendors.captain-gallery.create')

  {{-- Edit Modal --}}
  @include('vendors.captain-gallery.edit')
@endsection
