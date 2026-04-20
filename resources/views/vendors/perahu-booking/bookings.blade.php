@extends('vendors.layout')

@section('content')
<div class="page-header">
  @if (request()->routeIs('vendor.perahu_bookings.all_bookings'))
  <h4 class="page-title">{{ __('All Bookings') }}</h4>
  @elseif (request()->routeIs('vendor.perahu_bookings.paid_bookings'))
  <h4 class="page-title">{{ __('Paid Bookings') }}</h4>
  @elseif (request()->routeIs('vendor.perahu_bookings.unpaid_bookings'))
  <h4 class="page-title">{{ __('Unpaid Bookings') }}</h4>
  @endif

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
      <a href="#">{{ __('Perahu Bookings') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      @if (request()->routeIs('vendor.perahu_bookings.all_bookings'))
      <a href="#">{{ __('All Bookings') }}</a>
      @elseif (request()->routeIs('vendor.perahu_bookings.paid_bookings'))
      <a href="#">{{ __('Paid Bookings') }}</a>
      @elseif (request()->routeIs('vendor.perahu_bookings.unpaid_bookings'))
      <a href="#">{{ __('Unpaid Bookings') }}</a>
      @endif
    </li>
  </ul>
</div>
@php
$vendorId = Auth::guard('vendor')->user()->id;

if ($vendorId) {
$current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId);

if (!empty($current_package) && !empty($current_package->features)) {
$permissions = json_decode($current_package->features, true);
} else {
$permissions = null;
}
} else {
$permissions = null;
}
@endphp


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-lg-3">
            <div class="card-title">
              @if (request()->routeIs('vendor.perahu_bookings.all_bookings'))
              	{{ __('All Bookings') }}
              @elseif (request()->routeIs('vendor.perahu_bookings.paid_bookings'))
              	{{ __('Paid Bookings') }}
              @elseif (request()->routeIs('vendor.perahu_bookings.unpaid_bookings'))
              	{{ __('Unpaid Bookings') }}
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <form @if (request()->routeIs('vendor.perahu_bookings.all_bookings')) action="{{ route('vendor.perahu_bookings.all_bookings') }}"
              @elseif (request()->routeIs('vendor.perahu_bookings.paid_bookings')) action="{{ route('vendor.perahu_bookings.paid_bookings') }}"
              @elseif (request()->routeIs('vendor.perahu_bookings.unpaid_bookings')) action="{{ route('vendor.perahu_bookings.unpaid_bookings') }}" 
              @endif method="GET" id="booking_form">
              <div class="row">
                <div class="col-lg-6 p-1">
                  	<input name="booking_no" type="text" id="hotel_title" class="form-control form-control-sm" placeholder="{{ __('Booking No.') }}" value="{{ !empty(request()->input('booking_no')) ? request()->input('booking_no') : '' }}">
                </div>
                <div class="col-lg-6 p-1">
                  	<input name="title" type="text" id="room_title" class="form-control form-control-sm" placeholder="{{ __('Search Keyword...') }}" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
                </div>
                <input type="hidden" name="language" value="{{ request()->input('language') }}">
              </div>
            </form>
          </div>
          <div class="col-lg-3 text-right">
            @if (is_array($permissions) && in_array('Add Booking From Dashboard', $permissions))
              <a href="#" data-toggle="modal" data-target="#roomModal" class="btn btn-primary btn-sm mt-1">
                <i class="fas fa-plus"></i> {{ __('Add Booking') }}
              </a>
            @endif
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            @if (count($bookings) == 0)
            <h3 class="text-center mt-2">{{ __('NO PERAHU BOOKING FOUND') . '!' }}</h3>
            @else
            <div class="table-responsive">
              <table class="table table-striped mt-3">
                <thead>
                  <tr>
                    <th scope="col">{{ __('SL') . '#' }}</th>
                    <th scope="col">{{ __('Booking No') . '.' }}</th>
                    <th scope="col">{{ __('Detail Perahu') }}</th>
                    <th scope="col">{{ __('Penumpang') }}</th>
                    <th scope="col">{{ __('Total Bayar') }}</th>
                    <th scope="col">{{ __('Status Pesanan') }}</th>
                    <th scope="col">{{ __('Status Bayar') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($bookings as $booking)
                  <tr>
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        {{ '#' . $booking->order_number }}
                        <div class="mt-1">
                            @if($booking->age_confirmed)
                                <span class="badge badge-success badge-pill mt-1" style="font-size: 11px;">
                                    <i class="fas fa-check-circle mr-1"></i> {{ __('17+ Verified') }}
                                </span>
                            @else
                                <span class="badge badge-secondary badge-pill mt-1" style="font-size: 11px;">
                                    <i class="fas fa-clock mr-1"></i> {{ __('Unverified') }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                      @php
                      $roomInfo = null;
                      $roomhave = null;
                      if ($booking->hotelRoom) {
                        $roomInfo = $booking->hotelRoom->room_content
                        ->where('language_id', $defaultLang->id)
                        ->first();
                        $roomhave = 'done';
                      }
                      @endphp
                      @if ($roomInfo)
                        <div class="fw-bold">{{ $booking->hotelRoom->nama_km ?? '--' }}</div>
                        <div class="small text-muted">{{ __('Kapten') }}: {{ $booking->hotelRoom->nama_kapten ?? '--' }}</div>
                        <div class="small text-primary">{{ $roomInfo->title }}</div>
                      @else
                        --
                      @endif
                    </td>
                    <td>
                        <div>{{ $booking->booking_name }}</div>
                        <div class="small text-muted">{{ $booking->booking_phone }}</div>
                    </td>
                    <td>
                      Rp {{ number_format($booking->grand_total, 2, ',', '.') }}
                    </td>
                    <td>
                        @if($booking->order_status == 'pending')
                            <div class="d-flex">
                                <form action="{{ route('vendor.perahu_bookings.update_order_status', ['id' => $booking->id]) }}" method="POST" class="mr-1">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                    <input type="hidden" name="order_status" value="approved">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal-{{ $booking->id }}"><i class="fas fa-times"></i></button>
                            </div>
                        @else
                            <span class="badge badge-{{ $booking->order_status == 'approved' ? 'success' : 'danger' }} badge-pill">
                                {{ ucfirst(__($booking->order_status)) }}
                            </span>
                            @if($booking->rejection_reason)
                                <div class="mt-1"><i class="fas fa-info-circle text-danger" title="{{ $booking->rejection_reason }}"></i></div>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($booking->payment_status == 1)
                            <span class="badge badge-success badge-pill">{{ __('Paid') }}</span>
                        @else
                            <span class="badge badge-warning badge-pill">{{ __('Unpaid') }}</span>
                        @endif
                    </td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          {{ __('Select') }}
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a href="{{ route('vendor.perahu_bookings.booking_details', ['id' => $booking->id, 'language' => $defaultLang->code]) }}"
                            class="dropdown-item">
                            {{ __('Details') }}
                          </a>

                          @if ($roomhave)
                          @if (is_array($permissions) && in_array('Edit Booking From Dashboard', $permissions))
                          <a href="{{ route('vendor.perahu_bookings.booking_details_and_edit', ['id' => $booking->id, 'language' => $defaultLang->code]) }}"
                            class="dropdown-item">
                            {{ __('Edit & View') }}
                          </a>
                          @endif
                          @endif

                          @if (!is_null($booking->attachment))
                          <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                            data-target="#attachmentModal_{{ $booking->id }}">
                            {{ __('Attachment') }}
                          </a>
                          @endif
                          @if ($booking->invoice)
                          <a href="{{ asset('assets/file/invoices/perahu/' . $booking->invoice) }}" class="dropdown-item"
                            target="_blank">
                            {{ __('Invoice') }}
                          </a>
                          @endif

                          <a href="#" class="dropdown-item mailBtn" data-target="#mailModal" data-toggle="modal"
                            data-booking_email="{{ $booking->booking_email }}">
                            {{ __('Send Mail') }}
                          </a>

                          @if($booking->payment_status == '1' && $booking->user_id)
                          <a href="#" class="dropdown-item reviewBtn" data-target="#reviewModal_{{ $booking->id }}"
                            data-toggle="modal">
                            {{ __('Beri Ulasan Penumpang') }}
                          </a>
                          @endif
                        </div>
                      </div>
                    </td>
                  </tr>
                  @includeIf('vendors.perahu-booking.show-attachment')
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="row">
          <div class="d-inline-block mx-auto">
            {{ $bookings->appends([
            'booking_no' => request()->input('booking_no'),
            'title' => request()->input('title'),
            'language' => request()->input('language'),
            ])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@includeIf('vendors.perahu-booking.send-mail')
@includeIf('vendors.perahu-booking.all-rooms')

@foreach ($bookings as $booking)
@if($booking->payment_status == '1' && $booking->user_id)
<!-- Review Modal for Booking {{ $booking->id }} -->
<div class="modal fade" id="reviewModal_{{ $booking->id }}" tabindex="-1" role="dialog"
  aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">{{ __('Beri Ulasan Penumpang') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('vendor.review.user.store') }}" method="POST">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="user_id" value="{{ $booking->user_id }}">

        <div class="modal-body">
          <div class="form-group">
            <label>{{ __('Rating') }}</label>
            <select name="rating" class="form-control" required>
              <option value="5">5 - Sangat Baik</option>
              <option value="4">4 - Baik</option>
              <option value="3">3 - Cukup</option>
              <option value="2">2 - Buruk</option>
              <option value="1">1 - Sangat Buruk</option>
            </select>
          </div>
          <div class="form-group">
            <label>{{ __('Komentar') }}</label>
            <textarea name="review_text" class="form-control" rows="4" placeholder="{{ __('Opsional') }}"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tutup') }}</button>
          <button type="submit" class="btn btn-primary">{{ __('Kirim Ulasan') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endforeach

@foreach ($bookings as $booking)
<div class="modal fade" id="rejectModal-{{ $booking->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">{{ __('Tolak Pesanan') }} #{{ $booking->order_number }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('vendor.perahu_bookings.update_order_status', ['id' => $booking->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="order_status" value="rejected">
        <div class="modal-body">
          <div class="form-group">
            <label>{{ __('Alasan Penolakan') }} *</label>
            <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="{{ __('Contoh: Perahu sedang dalam perbaikan / maintenance.') }}"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
          <button type="submit" class="btn btn-danger">{{ __('Tolak Pesanan') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('assets/admin/js/admin-room.js') }}"></script>
@endsection