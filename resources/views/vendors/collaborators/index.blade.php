@extends('vendors.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Kelola Kolaborator (Co-Host)') }}</h4>
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
      <a href="#">{{ __('Kolaborator') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ __('Tambah Kolaborator') }}</h4>
      </div>
      <div class="card-body">
        <form action="{{ route('vendor.collaborators.store') }}" method="POST">
          @csrf
          <div class="form-group p-0">
            <label>{{ __('Email User') . '*' }}</label>
            <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
            <small class="text-muted">{{ __('User harus sudah terdaftar di Gofishi.') }}</small>
          </div>
          
          <div class="form-group p-0 mt-3">
            <label>{{ __('Peran (Role)') . '*' }}</label>
            <select name="role" class="form-control">
              <option value="co-host">{{ __('Co-Host (Full Access)') }}</option>
              <option value="editor">{{ __('Editor (Manage Listings)') }}</option>
              <option value="viewer">{{ __('Viewer (View Only)') }}</option>
            </select>
          </div>

          <button type="submit" class="btn btn-success btn-block mt-4">{{ __('Undang / Tambah') }}</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ __('Daftar Kolaborator Aktif') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ __('User') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Peran') }}</th>
                <th>{{ __('Aksi') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($collaborators as $collab)
                <tr>
                  <td>{{ $collab->user->username }}</td>
                  <td>{{ $collab->user->email }}</td>
                  <td><span class="badge badge-info">{{ ucfirst($collab->role) }}</span></td>
                  <td>
                    <form action="{{ route('vendor.collaborators.destroy', $collab->id) }}" method="POST" onsubmit="return confirm('Hapus kolaborator ini?')">
                      @csrf
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">{{ __('Belum ada kolaborator.') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
