@extends('admin.layout')

@section('content')
  <div class="page-header">
    @if (request()->routeIs('admin.perahu_bookings.all_bookings'))
      <h4 class="page-title">{{ __('All Bookings') }}</h4>
    @elseif (request()->routeIs('admin.perahu_bookings.paid_bookings'))
      <h4 class="page-title">{{ __('Paid Bookings') }}</h4>
    @elseif (request()->routeIs('admin.perahu_bookings.unpaid_bookings'))
      <h4 class="page-title">{{ __('Unpaid Bookings') }}</h4>
    @endif

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
        <a href="#">{{ __('Perahu Bookings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        @if (request()->routeIs('admin.perahu_bookings.all_bookings'))
          <a href="#">{{ __('All Bookings') }}</a>
        @elseif (request()->routeIs('admin.perahu_bookings.paid_bookings'))
          <a href="#">{{ __('Paid Bookings') }}</a>
        @elseif (request()->routeIs('admin.perahu_bookings.unpaid_bookings'))
          <a href="#">{{ __('Unpaid Bookings') }}</a>
        @endif
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-3">
              <div class="card-title">
                @if (request()->routeIs('admin.perahu_bookings.all_bookings'))
                  {{ __('All Bookings') }}
                @elseif (request()->routeIs('admin.perahu_bookings.paid_bookings'))
                  {{ __('Paid Bookings') }}
                @elseif (request()->routeIs('admin.perahu_bookings.unpaid_bookings'))
                  {{ __('Unpaid Bookings') }}
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <form
                @if (request()->routeIs('admin.perahu_bookings.all_bookings')) action="{{ route('admin.perahu_bookings.all_bookings') }}"
                @elseif (request()->routeIs('admin.perahu_bookings.paid_bookings'))
                  action="{{ route('admin.perahu_bookings.paid_bookings') }}"
                @elseif (request()->routeIs('admin.perahu_bookings.unpaid_bookings'))
                  action="{{ route('admin.perahu_bookings.unpaid_bookings') }}" @endif
                method="GET" id="booking_form">
                <div class="row">
                  <div class="col-lg-6">
                    <input name="booking_no" type="text" id="hotel_title" class="form-control"
                      placeholder="{{ __('Search By Booking No.') }}"
                      value="{{ !empty(request()->input('booking_no')) ? request()->input('booking_no') : '' }}">
                  </div>
                  <div class="col-lg-6">
                    <input name="title" type="text" id="room_title" class="form-control"
                      placeholder="{{ __('Search Here...') }}"
                      value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
                  </div>
                  <input type="hidden" name="language" value="{{ request()->input('language') }}" class="form-control"
                    placeholder="{{ __('language') }}">
                </div>
              </form>
            </div>

            <div class="col-lg-3">
              <a href="#" data-toggle="modal" data-target="#roomModal"
                class="btn btn-primary btn-sm float-lg-right float-left ml-lg-1 mt-1">
                {{ __('Add Booking') }}
              </a>
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mt-1 mb-1"
                data-href="{{ route('admin.perahu_bookings.bulk_delete_booking') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
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
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Booking No') . '.' }}</th>
                        <th scope="col">{{ __('Detail Perahu') }}</th>
                        <th scope="col">{{ __('Vendor/Customer') }}</th>
                        <th scope="col">{{ __('Total Bayar') }}</th>
                        <th scope="col">{{ __('Status Pesanan') }}</th>
                        <th scope="col">{{ __('Status Bayar') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookings as $booking)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $booking->id }}">
                          </td>
                          <td>
                              {{ '#' . $booking->order_number }}
                              <div class="mt-1">
                                  @if($booking->age_confirmed)
                                      <span class="badge badge-primary px-2 py-1" style="font-size: 10px;">
                                          <i class="fas fa-user-check mr-1"></i> {{ __('17+ Verified') }}
                                      </span>
                                  @else
                                      <span class="badge badge-secondary px-2 py-1" style="font-size: 10px;">
                                          <i class="fas fa-user-clock mr-1"></i> {{ __('Unverified') }}
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
                              <div class="fw-bold text-primary">{{ $booking->hotelRoom->nama_km ?? '--' }}</div>
                              <div class="small text-muted">{{ __('Kapten') }}: {{ $booking->hotelRoom->nama_kapten ?? '--' }}</div>
                              <div class="small text-dark">{{ strlen($roomInfo->title) > 25 ? mb_substr($roomInfo->title, 0, 25, 'utf-8') . '...' : $roomInfo->title }}</div>
                            @else
                              --
                            @endif
                          </td>
                          <td>
                            @if ($booking->vendor_id != 0)
                              @php
                                $vendor = $booking->vendor()->first();
                              @endphp
                              @if ($vendor)
                                <div class="small">Host: <a href="{{ route('admin.vendor_management.vendor_details', ['id' => $vendor->id, 'language' => $defaultLang->code]) }}">{{ $vendor->username }}</a></div>
                              @endif
                            @else
                              <div class="small">Host: <span class="badge badge-success">{{ __('Admin') }}</span></div>
                            @endif
                            <div class="small mt-1">Cust: {{ $booking->booking_name }}</div>
                          </td>
                          <td>
                            {{ $booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '' }}
                            {{ $booking->grand_total }}
                            {{ $booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '' }}
                          </td>
                          <td>
                              @if($booking->order_status == 'pending')
                                  <div class="btn-group">
                                      <form action="{{ route('admin.perahu_bookings.update_order_status', ['id' => $booking->id]) }}" method="POST">
                                          @csrf
                                          <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                          <input type="hidden" name="order_status" value="approved">
                                          <button type="submit" class="btn btn-success btn-xs mr-1">{{ __('Setujui') }}</button>
                                      </form>
                                      <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#rejectModal-{{ $booking->id }}">{{ __('Tolak') }}</button>
                                  </div>
                              @else
                                  <span class="badge badge-{{ $booking->order_status == 'approved' ? 'success' : 'danger' }}">
                                      {{ ucfirst(__($booking->order_status)) }}
                                  </span>
                                  @if($booking->rejection_reason)
                                      <div class="mt-1"><i class="fas fa-info-circle text-danger" title="{{ $booking->rejection_reason }}"></i></div>
                                  @endif
                              @endif
                          </td>
                          <td>
                            @if ($booking->payment_status == 0)
                              <form id="paymentStatusForm-{{ $booking->id }}" class="d-inline-block"
                                action="{{ route('admin.perahu_bookings.update_payment_status', ['id' => $booking->id]) }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $booking->id }}">
                                <select
                                  class="form-control form-control-sm bg-warning text-dark"
                                  name="payment_status"
                                  onchange="document.getElementById('paymentStatusForm-{{ $booking->id }}').submit()">
                                  <option value="0" selected>{{ __('Pending') }}</option>
                                  <option value="1">{{ __('Completed') }}</option>
                                  <option value="2">{{ __('Rejected') }}</option>
                                </select>
                              </form>
                            @else
                              <span class="badge badge-{{ $booking->payment_status == '1' ? 'success' : 'danger' }}">
                                {{ $booking->payment_status == '1' ? __('Completed') : __('Rejected') }}
                              </span>
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.perahu_bookings.booking_details', ['id' => $booking->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>
                                @if ($roomhave)
                                  <a href="{{ route('admin.perahu_bookings.booking_details_and_edit', ['id' => $booking->id, 'language' => $defaultLang->code]) }}"
                                    class="dropdown-item">
                                    {{ __('Edit & View') }}
                                  </a>
                                @endif

                                @if (!is_null($booking->attachment))
                                  <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                                    data-target="#attachmentModal_{{ $booking->id }}">
                                    {{ __('Attachment') }}
                                  </a>
                                @endif

                                @if ($booking->invoice)
                                  <a href="{{ asset('assets/file/invoices/perahu/' . $booking->invoice) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif

                                <a href="#" class="dropdown-item mailBtn" data-target="#mailModal"
                                  data-toggle="modal" data-booking_email="{{ $booking->booking_email }}">
                                  {{ __('Send Mail') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.perahu_bookings.delete_booking', ['id' => $booking->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>

                        @includeIf('admin.perahu-booking.show-attachment')
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

  @includeIf('admin.perahu-booking.send-mail')

  @includeIf('admin.perahu-booking.all-perahu')

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
        <form action="{{ route('admin.perahu_bookings.update_order_status', ['id' => $booking->id]) }}" method="POST">
          @csrf
          <input type="hidden" name="booking_id" value="{{ $booking->id }}">
          <input type="hidden" name="order_status" value="rejected">
          <div class="modal-body">
            <div class="form-group">
              <label>{{ __('Alasan Penolakan') }} *</label>
              <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="{{ __('Contoh: Jadwal tidak tersedia.') }}"></textarea>
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
