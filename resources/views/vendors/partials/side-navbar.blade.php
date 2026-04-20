@php
  $defaultLang = $langs->where('is_default', 1)->first();
  $roleInfo = $roleInfo ?? null;
@endphp
<div class="sidebar sidebar-style-2"
  data-background-color="{{ Auth::guard('vendor')->user()->vendor_theme_version == 'light' ? 'white' : 'dark2' }}">
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('vendor')->user()->photo != null)
            <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('vendor')->user()->photo) }}"
              alt="Vendor Photo" class="avatar-img rounded-circle">
          @else
            <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfile" aria-expanded="true">
            <span>
              {{ Auth::guard('vendor')->user()->username }}
              <span class="user-level">{{ __('Vendor') }}</span>
              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfile">
            <ul class="nav">
              <li>
                <a href="{{ route('vendor.edit.profile') }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('vendor.change_password') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('vendor.logout') }}">
                  <span class link-collapse>{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>


      <ul class="nav nav-primary">
        {{-- Dashboard --}}
        <li class="nav-item @if (request()->routeIs('vendor.dashboard')) active @endif">
          <a href="{{ route('vendor.dashboard') }}">
            <i class="fal fa-chart-pie"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>

        {{-- LOKASI MANAGEMENT --}}
        <li
          class="nav-item @if (request()->routeIs('vendor.lokasi_management.lokasi')) active
            @elseif (request()->routeIs('vendor.lokasi_management.create_lokasi')) active
            @elseif (request()->routeIs('vendor.lokasi_management.edit_lokasi')) active @endif">
          <a data-toggle="collapse" href="#lokasi">
            <i class="fal fa-map-marker-alt"></i>
            <p>{{ __('Lokasi Dermaga') }}</p>
            <span class="caret"></span>
          </a>

          <div id="lokasi"
            class="collapse
              @if (request()->routeIs('vendor.lokasi_management.lokasi')) show
              @elseif (request()->routeIs('vendor.lokasi_management.create_lokasi')) show
              @elseif (request()->routeIs('vendor.lokasi_management.edit_lokasi')) show @endif">
            <ul class="nav nav-collapse">
              <li
                class="{{ request()->routeIs('vendor.lokasi_management.lokasi') || request()->routeIs('vendor.lokasi_management.edit_lokasi') ? 'active' : '' }}">
                <a href="{{ route('vendor.lokasi_management.lokasi', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Semua Lokasi') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.lokasi_management.create_lokasi') ? 'active' : '' }}">
                <a href="{{ route('vendor.lokasi_management.create_lokasi', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Tambah Lokasi') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- PERAHU MANAGEMENT --}}
        <li
          class="nav-item @if (request()->routeIs('vendor.perahu_management.perahu')) active
            @elseif (request()->routeIs('vendor.perahu_management.create_perahu')) active
            @elseif (request()->routeIs('vendor.perahu_management.edit_perahu')) active
            @elseif (request()->routeIs('vendor.perahu_management.coupons')) active
            @elseif (request()->routeIs('vendor.perahu_management.create_coupon')) active
            @elseif (request()->routeIs('vendor.perahu_management.edit_coupon')) active
            @elseif (request()->routeIs('vendor.perahu.reviews')) active @endif">
          <a data-toggle="collapse" href="#room">
            <i class="fal fa-ship"></i>
            <p>{{ __('Pengaturan Perahu') }}</p>
            <span class="caret"></span>
          </a>

          <div id="room"
            class="collapse
              @if (request()->routeIs('vendor.perahu_management.perahu')) show
              @elseif (request()->routeIs('vendor.perahu_management.create_perahu')) show
              @elseif (request()->routeIs('vendor.perahu_management.edit_perahu')) show
              @elseif (request()->routeIs('vendor.perahu_management.coupons')) show
              @elseif (request()->routeIs('vendor.perahu_management.create_coupon')) show
              @elseif (request()->routeIs('vendor.perahu_management.edit_coupon')) show
              @elseif (request()->routeIs('vendor.perahu.reviews')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.perahu_management.coupons') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_management.coupons', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Kupon') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.perahu_management.create_room') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_management.create_perahu', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Tambah Perahu') }}</span>
                </a>
              </li>

              <li
                class="{{ request()->routeIs('vendor.perahu_management.perahu') || request()->routeIs('vendor.perahu_management.edit_perahu') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_management.perahu', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Kelola Perahu') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.perahu.reviews') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu.reviews') }}">
                  <span class="sub-item">{{ __('Ulasan Perahu') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Chat Inbox --}}
        <li class="nav-item @if (request()->routeIs('vendor.chat.inbox')) active @endif">
          <a href="{{ route('vendor.chat.inbox') }}">
            <i class="fas fa-comments"></i>
            <p>{{ __('Inbox Chat') }}</p>
          </a>
        </li>



        {{-- ROOM BOOKINGS --}}
        <li
          class="nav-item @if (request()->routeIs('vendor.perahu_bookings.all_bookings')) active
            @elseif (request()->routeIs('vendor.perahu_bookings.paid_bookings')) active
            @elseif (request()->routeIs('vendor.perahu_bookings.unpaid_bookings')) active
            @elseif (request()->routeIs('vendor.perahu_bookings.report')) active
            @elseif (request()->routeIs('vendor.perahu_bookings.booking_details_and_edit')) active
            @elseif (request()->routeIs('vendor.perahu_bookings.booking_details')) active
            @elseif (request()->routeIs('vendor.perahu_bookings.booking_form')) active @endif">
          <a data-toggle="collapse" href="#roomBookings">
            <i class="far fa-calendar-check"></i>
            <p class="pr-2">{{ __('Pemesanan Perahu') }}</p>
            <span class="caret"></span>
          </a>
          <div id="roomBookings"
            class="collapse
              @if (request()->routeIs('vendor.perahu_bookings.all_bookings')) show
              @elseif (request()->routeIs('vendor.perahu_bookings.paid_bookings')) show
              @elseif (request()->routeIs('vendor.perahu_bookings.unpaid_bookings')) show
              @elseif (request()->routeIs('vendor.perahu_bookings.report')) show
              @elseif (request()->routeIs('vendor.perahu_bookings.booking_details')) show
              @elseif (request()->routeIs('vendor.perahu_bookings.booking_details_and_edit')) show
              @elseif (request()->routeIs('vendor.perahu_bookings.booking_form')) show @endif">
            <ul class="nav nav-collapse">
              <li
                class="{{ request()->routeIs('vendor.perahu_bookings.all_bookings') || request()->routeIs('vendor.perahu_bookings.booking_details') || request()->routeIs('vendor.perahu_bookings.booking_details_and_edit') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_bookings.all_bookings') }}">
                  <span class="sub-item">{{ __('Semua Booking') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.perahu_bookings.paid_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_bookings.paid_bookings') }}">
                  <span class="sub-item">{{ __('Booking Dibayar') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.perahu_bookings.unpaid_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_bookings.unpaid_bookings') }}">
                  <span class="sub-item">{{ __('Booking Belum Bayar') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.perahu_bookings.report') ? 'active' : '' }}">
                <a href="{{ route('vendor.perahu_bookings.report') }}">
                  <span class="sub-item">{{ __('Laporan Transaksi') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Withdrawal --}}
        <li
          class="nav-item @if (request()->routeIs('vendor.withdraw')) active
            @elseif (request()->routeIs('vendor.withdraw.create')) active @endif">
          <a href="{{ route('vendor.withdraw', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-minus-circle"></i>
            <p>{{ __('Penarikan Dana') }}</p>
          </a>
        </li>


      </ul>
    </div>
  </div>
</div>
