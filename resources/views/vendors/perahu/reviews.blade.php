@extends('vendors.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Perahu Reviews') }}</h4>
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
      <a href="#">{{ __('Perahu Reviews') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ __('Ulasan Pelanggan') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped mt-3">
            <thead>
              <tr>
                <th>{{ __('Perahu') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Rating') }}</th>
                <th>{{ __('Ulasan') }}</th>
                <th>{{ __('Tanggapan Anda') }}</th>
                <th>{{ __('Aksi') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reviews as $review)
                <tr>
                  <td>{{ $review->room->room_content->first() ? $review->room->room_content->first()->title : 'Perahu' }}</td>
                  <td>{{ $review->user ? $review->user->username : 'Guest' }}</td>
                  <td>
                    <div class="text-warning">
                      @for($i=1; $i<=5; $i++)
                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                      @endfor
                    </div>
                  </td>
                  <td>{{ Str::limit($review->review, 50) }}</td>
                  <td>
                    @if($review->reply)
                      <span class="text-success">{{ Str::limit($review->reply, 50) }}</span>
                    @else
                      <span class="text-muted small"><i>{{ __('Belum ada tanggapan') }}</i></span>
                    @endif
                  </td>
                  <td>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#replyModal{{ $review->id }}">
                      {{ $review->reply ? __('Edit Tanggapan') : __('Beri Tanggapan') }}
                    </button>
                  </td>
                </tr>

                {{-- Reply Modal --}}
                <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tanggapan Ulasan') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ route('vendor.perahu.review.reply', $review->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                          <div class="form-group p-0">
                            <p class="mb-2"><strong>Ulasan User:</strong> <br> "{{ $review->review }}"</p>
                            <label>{{ __('Tanggapan Anda') }}</label>
                            <textarea name="reply" class="form-control" rows="5" placeholder="{{ __('Tulis tanggapan Anda di sini...') }}">{{ $review->reply }}</textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
                          <button type="submit" class="btn btn-success">{{ __('Simpan Tanggapan') }}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="6" class="text-center">{{ __('Belum ada ulasan.') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $reviews->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
