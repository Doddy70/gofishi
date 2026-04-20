<div class="sidebar sidebar-style-2"
  data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark2' }}">
  @php
    $roleInfo = $roleInfo ?? null;
  @endphp
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('admin')->user()->image != null)
          <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}" alt="Admin Image"
            class="avatar-img rounded-circle">
          @else
          <img src="{{ asset('assets/img/blank_user.jpg') }}" alt="" class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('admin')->user()->first_name }}

              @if (is_null($roleInfo))
              <span class="user-level">{{ __('Super Admin') }}</span>
              @else
              <span class="user-level">{{ $roleInfo->name }}</span>
              @endif

              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('admin.edit_profile') }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.change_password') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.logout') }}">
                  <span class="link-collapse">{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      @php
      if (!is_null($roleInfo)) {
      $rolePermissions = json_decode($roleInfo->permissions);
      }
      @endphp

      <ul class="nav nav-primary">
        {{-- search --}}
        <div class="row mb-3">
          <div class="col-12">
            <form action="">
              <div class="form-group py-0">
                <input name="term" type="text"
                  class="form-control sidebar-search {{ $defaultLang->direction == 1 ? 'rtr' : 'ltl' }}"
                  placeholder="{{ __('Cari Menu...') }}">
              </div>
              <input type="hidden" name="language" value="{{ request()->language }}">

            </form>
          </div>
        </div>
        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
          <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
            <i class="fas fa-chart-line"></i>
            <p>{{ __('Dasbor') }}</p>
          </a>
        </li>
        {{-- Lokasi Management --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Lokasi Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs([
                  'admin.lokasi_management.lokasi',
                  'admin.lokasi_management.create_lokasi',
                  'admin.lokasi_management.lokasi.holiday',
                  'admin.lokasi_management.edit_lokasi',
                  'admin.lokasi_management.categories',
                  'admin.lokasi_management.settings',
                  'admin.lokasi_management.select_vendor',
                  'admin.lokasi_management.manage_counter_section',
                  'admin.lokasi_management.amenities',
                  'admin.lokasi_management.featured_lokasi.charge',
                  'admin.lokasi_management.featured_lokasi.all_request',
                  'admin.lokasi_management.featured_lokasi.pending_request',
                  'admin.lokasi_management.featured_lokasi.approved_request',
                  'admin.lokasi_management.featured_lokasi.rejected_request',
                  'admin.lokasi_management.location.countries',
                  'admin.lokasi_management.location.city',
                  'admin.lokasi_management.location.states',
              ])) active @endif">
          <a data-toggle="collapse" href="#hotelManagement">
            <i class="fas fa-map-marked-alt"></i>
            <p>{{ __('Pengaturan Lokasi') }}</p>
            <span class="caret"></span>
          </a>

          <div id="hotelManagement" class="collapse 
             @if (request()->routeIs([
                     'admin.lokasi_management.lokasi',
                     'admin.lokasi_management.create_lokasi',
                     'admin.lokasi_management.lokasi.holiday',
                     'admin.lokasi_management.edit_lokasi',
                     'admin.lokasi_management.categories',
                     'admin.lokasi_management.settings',
                     'admin.lokasi_management.amenities',
                     'admin.lokasi_management.select_vendor',
                     'admin.lokasi_management.manage_counter_section',
                     'admin.lokasi_management.featured_lokasi.charge',
                     'admin.lokasi_management.featured_lokasi.all_request',
                     'admin.lokasi_management.featured_lokasi.pending_request',
                     'admin.lokasi_management.featured_lokasi.approved_request',
                     'admin.lokasi_management.featured_lokasi.rejected_request',
                     'admin.lokasi_management.location.countries',
                     'admin.lokasi_management.location.city',
                     'admin.lokasi_management.location.states',
                 ])) show @endif">
            <ul class="nav nav-collapse">

              <li class="{{ request()->routeIs('admin.lokasi_management.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.lokasi_management.settings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Pengaturan') }}</span>
                </a>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#specifications" aria-expanded="{{ request()->routeIs('admin.lokasi_management.location.countries') ||
                    request()->routeIs('admin.lokasi_management.location.city') ||
                    request()->routeIs('admin.lokasi_management.categories') ||
                    request()->routeIs('admin.lokasi_management.amenities') ||
                    request()->routeIs('admin.lokasi_management.location.states')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Spesifikasi') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="specifications" class="collapse 
                    @if (request()->routeIs('admin.lokasi_management.location.countries')) show 
                    @elseif (request()->routeIs('admin.lokasi_management.location.city')) show
                    @elseif (request()->routeIs('admin.lokasi_management.categories')) show
                    @elseif (request()->routeIs('admin.lokasi_management.amenities')) show
                    @elseif (request()->routeIs('admin.lokasi_management.location.states')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.lokasi_management.categories') ? 'active' : '' }}">
                      <a href="{{ route('admin.lokasi_management.categories', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Kategori') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.lokasi_management.amenities') ? 'active' : '' }}">
                      <a href="{{ route('admin.lokasi_management.amenities', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Fasilitas') }}</span>
                      </a>
                    </li>

                    <li class="submenu">
                      <a data-toggle="collapse" href="#set-location"
                        aria-expanded="{{ request()->routeIs('admin.lokasi_management.location.countries') || request()->routeIs('admin.lokasi_management.location.city') || request()->routeIs('admin.lokasi_management.location.states') ? 'true' : 'false' }}">
                        <span class="sub-item">{{ __('Lokasi') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="set-location" class="collapse 
                    @if (request()->routeIs('admin.lokasi_management.location.countries')) show 
                    @elseif (request()->routeIs('admin.lokasi_management.location.city')) show
                    @elseif (request()->routeIs('admin.lokasi_management.location.states')) show @endif">
                        <ul class="nav nav-collapse subnav">
                          <li
                            class="{{ request()->routeIs('admin.lokasi_management.location.countries') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.location.countries', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Negara') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.lokasi_management.location.states') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.location.states', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Provinsi') }}</span>
                            </a>
                          </li>
                          <li class="{{ request()->routeIs('admin.lokasi_management.location.city') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.location.city', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Kota') }}</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#manage-hotels" aria-expanded="{{ request()->routeIs('admin.lokasi_management.lokasi') ||
                    request()->routeIs('admin.lokasi_management.select_vendor') ||
                    request()->routeIs('admin.lokasi_management.edit_lokasi') ||
                    request()->routeIs('admin.lokasi_management.manage_counter_section') ||
                    request()->routeIs('admin.lokasi_management.create_lokasi')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Kelola Lokasi') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="manage-hotels" class="collapse 
                    @if (request()->routeIs('admin.lokasi_management.lokasi')) show 
                    @elseif (request()->routeIs('admin.lokasi_management.select_vendor')) show
                    @elseif (request()->routeIs('admin.lokasi_management.edit_lokasi')) show
                    @elseif (request()->routeIs('admin.lokasi_management.manage_counter_section')) show
                    @elseif (request()->routeIs('admin.lokasi_management.create_lokasi')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class=" @if (request()->routeIs('admin.lokasi_management.select_vendor')) active
                   @elseif (request()->routeIs('admin.lokasi_management.create_lokasi')) active @endif">
                      <a href="{{ route('admin.lokasi_management.select_vendor', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Tambah Lokasi') }}</span>
                      </a>
                    </li>
                    <li class=" @if (request()->routeIs('admin.lokasi_management.lokasi')) active
                   @elseif (request()->routeIs('admin.lokasi_management.edit_lokasi')) active 
                   @elseif (request()->routeIs('admin.lokasi_management.manage_counter_section')) active @endif">
                      <a href="{{ route('admin.lokasi_management.lokasi', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Kelola Lokasi') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              {{-- lokasi featured --}}
              <li class="submenu">
                <a data-toggle="collapse" href="#featured-hotel" aria-expanded="{{ request()->routeIs([
                        'admin.lokasi_management.featured_lokasi.charge',
                        'admin.lokasi_management.featured_lokasi.all_request',
                        'admin.lokasi_management.featured_lokasi.pending_request',
                        'admin.lokasi_management.featured_lokasi.approved_request',
                        'admin.lokasi_management.featured_lokasi.rejected_request',
                    ])
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Lokasi Unggulan') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="featured-hotel" class="collapse {{ request()->routeIs([
                        'admin.lokasi_management.featured_lokasi.charge',
                        'admin.lokasi_management.featured_lokasi.all_request',
                        'admin.lokasi_management.featured_lokasi.pending_request',
                        'admin.lokasi_management.featured_lokasi.approved_request',
                        'admin.lokasi_management.featured_lokasi.rejected_request',
                    ])
                        ? 'show'
                        : '' }}">
                  <ul class="nav nav-collapse subnav">
                    <li
                      class="{{ request()->routeIs('admin.lokasi_management.featured_lokasi.charge') ? 'active' : '' }}">
                      <a href="{{ route('admin.lokasi_management.featured_lokasi.charge') }}">
                        <span class="sub-item">{{ __('Biaya') }}</span>
                      </a>
                    </li>

                    <li class="submenu">
                      <a data-toggle="collapse" href="#requests" aria-expanded="{{ request()->routeIs('admin.lokasi_management.featured_lokasi.approved_request') ||
                          request()->routeIs('admin.lokasi_management.featured_lokasi.pending_request') ||
                          request()->routeIs('admin.lokasi_management.featured_lokasi.rejected_request') ||
                          request()->routeIs('admin.lokasi_management.featured_lokasi.all_request')
                              ? 'true'
                              : 'false' }}">
                        <span class="sub-item">{{ __('Permintaan') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="requests" class="collapse 
                    @if (request()->routeIs('admin.lokasi_management.featured_lokasi.approved_request')) show 
                    @elseif (request()->routeIs('admin.lokasi_management.featured_lokasi.pending_request')) show
                    @elseif (request()->routeIs('admin.lokasi_management.featured_lokasi.rejected_request')) show
                    @elseif (request()->routeIs('admin.lokasi_management.featured_lokasi.all_request')) show @endif">
                        <ul class="nav nav-collapse subnav">
                          <li
                            class="{{ request()->routeIs('admin.lokasi_management.featured_lokasi.all_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.featured_lokasi.all_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Semua') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.lokasi_management.featured_lokasi.pending_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.featured_lokasi.pending_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Menunggu') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.lokasi_management.featured_lokasi.approved_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.featured_lokasi.approved_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Disetujui') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.lokasi_management.featured_lokasi.rejected_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.lokasi_management.featured_lokasi.rejected_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Ditolak') }}</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>

                  </ul>
                </div>
              </li>

              <li class=" @if (request()->routeIs('admin.lokasi_management.lokasi.holiday')) active @endif">
                <a
                  href="{{ route('admin.lokasi_management.lokasi.holiday', ['language' => $defaultLang->code, 'vendor_id' => 'admin']) }}">
                  <span class="sub-item">{{ __('Hari Libur') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif
        {{-- End Lokasi Management --}}

        {{-- ROOMS management --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Perahu Management', $rolePermissions)))
        <li class="nav-item
        {{ request()->routeIs([
            'admin.perahu_management.rooms',
            'admin.perahu_management.create_perahu',
            'admin.perahu_management.coupons',
            'admin.perahu_management.featured_perahu.charge',
            'admin.perahu_management.manage_additional_service',
            'admin.perahu_management.select_vendor',
            'admin.perahu_management.settings',
            'admin.perahu_management.categories',
            'admin.perahu_management.edit_perahu',
            'admin.perahu_management.featured_perahu.all_request',
            'admin.perahu_management.featured_perahu.pending_request',
            'admin.perahu_management.featured_perahu.approved_request',
            'admin.perahu_management.featured_perahu.rejected_request',
            'admin.perahu_management.additional_services',
            'admin.perahu_management.tax_amount',
            'admin.perahu_management.booking_hours',
        ])
            ? 'active'
            : '' }}">
          <a data-toggle="collapse" href="#roomManagement">
            <i class="fas fa-ship"></i>
            <p>{{ __('Pengaturan Perahu') }}</p>
            <span class="caret"></span>
          </a>
          <div id="roomManagement" class="collapse
            {{ request()->routeIs([
                'admin.perahu_management.rooms',
                'admin.perahu_management.create_perahu',
                'admin.perahu_management.featured_perahu.charge',
                'admin.perahu_management.manage_additional_service',
                'admin.perahu_management.select_vendor',
                'admin.perahu_management.categories',
                'admin.perahu_management.settings',
                'admin.perahu_management.edit_perahu',
                'admin.perahu_management.coupons',
                'admin.perahu_management.featured_perahu.all_request',
                'admin.perahu_management.featured_perahu.pending_request',
                'admin.perahu_management.featured_perahu.approved_request',
                'admin.perahu_management.featured_perahu.rejected_request',
                'admin.perahu_management.additional_services',
                'admin.perahu_management.booking_hours',
                'admin.perahu_management.tax_amount',
            ])
                ? 'show'
                : '' }}">
            <ul class="nav nav-collapse">

              <li class="submenu">
                <a data-toggle="collapse" href="#settings-room" aria-expanded="{{ request()->routeIs('admin.perahu_management.settings') ||
                    request()->routeIs('admin.perahu_management.tax_amount') ||
                    request()->routeIs('admin.perahu_management.coupons')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Pengaturan') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="settings-room" class="collapse 
                    @if (request()->routeIs('admin.perahu_management.settings')) show 
                    @elseif (request()->routeIs('admin.perahu_management.tax_amount')) show
                    @elseif (request()->routeIs('admin.perahu_management.coupons')) show @endif">
                  <ul class="nav nav-collapse subnav">

                    <li class="{{ request()->routeIs('admin.perahu_management.coupons') ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.coupons', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Kupon') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.perahu_management.tax_amount') ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.tax_amount', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Tax Amount') }}</span>
                      </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.perahu_management.settings') ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.settings', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('View') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#specifications-room" aria-expanded="{{ request()->routeIs('admin.perahu_management.booking_hours') ||
                    request()->routeIs('admin.perahu_management.additional_services') ||
                    request()->routeIs('admin.perahu_management.categories')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Spesifikasi') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="specifications-room" class="collapse 
                    @if (request()->routeIs('admin.perahu_management.booking_hours')) show 
                    @elseif (request()->routeIs('admin.perahu_management.additional_services')) show
                    @elseif (request()->routeIs('admin.perahu_management.categories')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.perahu_management.categories') ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.categories', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Kategori') }}</span>
                      </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.perahu_management.additional_services') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.perahu_management.additional_services', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Additional Services') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.perahu_management.booking_hours') ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.booking_hours', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Booking Hours') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#manage-rooms" aria-expanded="{{ request()->routeIs('admin.perahu_management.manage_additional_service') ||
                    request()->routeIs('admin.perahu_management.edit_perahu') ||
                    request()->routeIs('admin.perahu_management.rooms') ||
                    request()->routeIs('admin.perahu_management.create_perahu') ||
                    request()->routeIs('admin.perahu_management.select_vendor')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Kelola Perahu') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="manage-rooms" class="collapse 
                    @if (request()->routeIs('admin.perahu_management.manage_additional_service')) show 
                    @elseif (request()->routeIs('admin.perahu_management.rooms')) show
                    @elseif (request()->routeIs('admin.perahu_management.edit_perahu')) show
                    @elseif (request()->routeIs('admin.perahu_management.create_perahu')) show
                    @elseif (request()->routeIs('admin.perahu_management.select_vendor')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li
                      class="{{ request()->routeIs(['admin.perahu_management.select_vendor', 'admin.perahu_management.create_perahu']) ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.select_vendor') }}">
                        <span class="sub-item">{{ __('Tambah Perahu') }}</span>
                      </a>
                    </li>

                    <li
                      class="{{ request()->routeIs(['admin.perahu_management.rooms', 'admin.perahu_management.edit_perahu', 'admin.perahu_management.manage_additional_service']) ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.rooms', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Perahu') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              {{-- perahu featured --}}

              <li class="submenu">
                <a data-toggle="collapse" href="#featured-room" aria-expanded="{{ request()->routeIs([
                        'admin.perahu_management.featured_perahu.charge',
                        'admin.perahu_management.featured_perahu.all_request',
                        'admin.perahu_management.featured_perahu.pending_request',
                        'admin.perahu_management.featured_perahu.approved_request',
                        'admin.perahu_management.featured_perahu.rejected_request',
                    ])
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Perahu Unggulan') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="featured-room" class="collapse {{ request()->routeIs([
                        'admin.perahu_management.featured_perahu.charge',
                        'admin.perahu_management.featured_perahu.all_request',
                        'admin.perahu_management.featured_perahu.pending_request',
                        'admin.perahu_management.featured_perahu.approved_request',
                        'admin.perahu_management.featured_perahu.rejected_request',
                    ])
                        ? 'show'
                        : '' }}">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.perahu_management.featured_perahu.charge') ? 'active' : '' }}">
                      <a href="{{ route('admin.perahu_management.featured_perahu.charge') }}">
                        <span class="sub-item">{{ __('Biaya') }}</span>
                      </a>
                    </li>

                    <li class="submenu">
                      <a data-toggle="collapse" href="#requests" aria-expanded="{{ request()->routeIs('admin.perahu_management.featured_perahu.approved_request') ||
                          request()->routeIs('admin.perahu_management.featured_perahu.pending_request') ||
                          request()->routeIs('admin.perahu_management.featured_perahu.rejected_request') ||
                          request()->routeIs('admin.perahu_management.featured_perahu.all_request')
                              ? 'true'
                              : 'false' }}">
                        <span class="sub-item">{{ __('Permintaan') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="requests" class="collapse 
                    @if (request()->routeIs('admin.perahu_management.featured_perahu.approved_request')) show 
                    @elseif (request()->routeIs('admin.perahu_management.featured_perahu.pending_request')) show
                    @elseif (request()->routeIs('admin.perahu_management.featured_perahu.rejected_request')) show
                    @elseif (request()->routeIs('admin.perahu_management.featured_perahu.all_request')) show @endif">
                        <ul class="nav nav-collapse subnav">
                          <li
                            class="{{ request()->routeIs('admin.perahu_management.featured_perahu.all_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.perahu_management.featured_perahu.all_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Semua') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.perahu_management.featured_perahu.pending_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.perahu_management.featured_perahu.pending_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Menunggu') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.perahu_management.featured_perahu.approved_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.perahu_management.featured_perahu.approved_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Disetujui') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.perahu_management.featured_perahu.rejected_request') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.perahu_management.featured_perahu.rejected_request', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Ditolak') }}</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>

                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </li>
        @endif
        {{-- End Perahu management --}}

        {{-- ROOM BOOKINGS --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Perahu Bookings', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.perahu_bookings.all_bookings')) active
            @elseif (request()->routeIs('admin.perahu_bookings.paid_bookings')) active
            @elseif (request()->routeIs('admin.perahu_bookings.unpaid_bookings')) active
            @elseif (request()->routeIs('admin.perahu_bookings.report')) active
            @elseif (request()->routeIs('admin.perahu_bookings.booking_details_and_edit')) active
            @elseif (request()->routeIs('admin.perahu_bookings.booking_details')) active
            @elseif (request()->routeIs('admin.perahu_bookings.booking_form')) active @endif">
          <a data-toggle="collapse" href="#roomBookings">
            <i class="far fa-calendar-check"></i>
            <p class="pr-2">{{ __('Pemesanan Perahu') }}</p>
            <span class="caret"></span>
          </a>
          <div id="roomBookings" class="collapse
              @if (request()->routeIs('admin.perahu_bookings.all_bookings')) show
              @elseif (request()->routeIs('admin.perahu_bookings.paid_bookings')) show
              @elseif (request()->routeIs('admin.perahu_bookings.unpaid_bookings')) show
              @elseif (request()->routeIs('admin.perahu_bookings.report')) show
              @elseif (request()->routeIs('admin.perahu_bookings.booking_details')) show
              @elseif (request()->routeIs('admin.perahu_bookings.booking_details_and_edit')) show
              @elseif (request()->routeIs('admin.perahu_bookings.booking_form')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.perahu_bookings.all_bookings') ? 'active' : '' }}">
                <a href="{{ route('admin.perahu_bookings.all_bookings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Semua Pemesanan') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('admin.perahu_bookings.paid_bookings') ? 'active' : '' }}">
                <a href="{{ route('admin.perahu_bookings.paid_bookings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Pemesanan Dibayar') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('admin.perahu_bookings.unpaid_bookings') ? 'active' : '' }}">
                <a href="{{ route('admin.perahu_bookings.unpaid_bookings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Pemesanan Belum Dibayar') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('admin.perahu_bookings.report') ? 'active' : '' }}">
                <a href="{{ route('admin.perahu_bookings.report') }}">
                  <span class="sub-item">{{ __('Laporan') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- user --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
        <li
          class="nav-item @if (request()->routeIs('admin.user_management.registered_users')) active 
            @elseif (request()->routeIs('admin.user_management.registered_user.create')) active 
            @elseif (request()->routeIs('admin.user_management.registered_user.view')) active 
            @elseif (request()->routeIs('admin.user_management.registered_user.edit')) active 
            @elseif (request()->routeIs('admin.user_management.user.change_password')) active 
            @elseif (request()->routeIs('admin.user_management.subscribers')) active 
            @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active 
            @elseif (request()->routeIs('admin.user_management.push_notification.settings')) active 
            @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif">
          <a data-toggle="collapse" href="#user">
            <i class="fas fa-users"></i>
            <p>{{ __('Manajemen Pengguna') }}</p>
            <span class="caret"></span>
          </a>

          <div id="user"
            class="collapse 
              @if (request()->routeIs('admin.user_management.registered_users')) show 
              @elseif (request()->routeIs('admin.user_management.registered_user.create')) show 
              @elseif (request()->routeIs('admin.user_management.registered_user.view')) show 
              @elseif (request()->routeIs('admin.user_management.registered_user.edit')) show 
              @elseif (request()->routeIs('admin.user_management.user.change_password')) show 
              @elseif (request()->routeIs('admin.user_management.subscribers')) show 
              @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) show 
              @elseif (request()->routeIs('admin.user_management.push_notification.settings')) show 
              @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.user_management.registered_users')) active 
                  @elseif (request()->routeIs('admin.user_management.user.change_password')) active
                  @elseif (request()->routeIs('admin.user_management.registered_user.view')) active
                  @elseif (request()->routeIs('admin.user_management.registered_user.edit'))
                  active @endif
                  ">
                <a href="{{ route('admin.user_management.registered_users', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Registered Users') }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.user_management.registered_user.create')) active @endif
                  ">
                <a
                  href="{{ route('admin.user_management.registered_user.create', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Add User') }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('admin.user_management.subscribers')) active 
                  @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif">
                <a href="{{ route('admin.user_management.subscribers', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Subscribers') }}</span>
                </a>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#push_notification">
                  <span class="sub-item">{{ __('Push Notification') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="push_notification"
                  class="collapse 
                    @if (request()->routeIs('admin.user_management.push_notification.settings')) show 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li
                      class="{{ request()->routeIs('admin.user_management.push_notification.settings') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.user_management.push_notification.settings', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Pengaturan') }}</span>
                      </a>
                    </li>

                    <li
                      class="{{ request()->routeIs('admin.user_management.push_notification.notification_for_visitors') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.user_management.push_notification.notification_for_visitors', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Send Notification') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- vendor --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.vendor_management.registered_vendor')) active
            @elseif (request()->routeIs('admin.vendor_management.add_vendor')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
            @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
            @elseif (request()->routeIs('admin.vendor_management.settings')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
          <a data-toggle="collapse" href="#vendor">
            <i class="fas fa-store"></i>
            <p>{{ __('Manajemen Vendor') }}</p>
            <span class="caret"></span>
          </a>

          <div id="vendor" class="collapse
              @if (request()->routeIs('admin.vendor_management.registered_vendor')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor_details')) show
              @elseif (request()->routeIs('admin.edit_management.vendor_edit')) show
              @elseif (request()->routeIs('admin.vendor_management.add_vendor')) show
              @elseif (request()->routeIs('admin.vendor_management.settings')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.vendor_management.settings')) active @endif">
                <a href="{{ route('admin.vendor_management.settings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Pengaturan') }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.vendor_management.registered_vendor')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                  @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
                <a href="{{ route('admin.vendor_management.registered_vendor', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Registered vendors') }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.vendor_management.add_vendor')) active @endif">
                <a href="{{ route('admin.vendor_management.add_vendor', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Add vendor') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- Moderasi Ulasan --}}
        <li class="nav-item @if (request()->routeIs('admin.user_reviews.index')) active @endif">
          <a href="{{ route('admin.user_reviews.index') }}">
            <i class="fas fa-star-half-alt"></i>
            <p>{{ __('Moderasi Ulasan') }}</p>
          </a>
        </li>

        {{-- Subscription log --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && (in_array('Subscription Log', $rolePermissions) || in_array('Payment Log', $rolePermissions))))
        <li class="nav-item @if (request()->routeIs('admin.subscription-log')) active @endif">
          <a href="{{ route('admin.subscription-log', ['language' => $defaultLang->code]) }}">
            <i class="fas fa-clipboard-list"></i>
            <p>{{ __('Log Berlangganan') }}</p>
          </a>
        </li>
        @endif
        {{-- End Subscription Log --}}

        {{-- withdraw method --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Withdrawals Management', $rolePermissions)))
        <li class="nav-item
          @if (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) active
          @elseif (request()->routeIs('admin.withdraw.withdraw_request')) active @endif">
          <a data-toggle="collapse" href="#withdraw_method">
            <i class="fas fa-wallet"></i>
            <p>{{ __('Manajemen Penarikan') }}</p>
            <span class="caret"></span>
          </a>

          <div id="withdraw_method" class="collapse
            @if (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) show
            @elseif (request()->routeIs('admin.withdraw.withdraw_request')) show @endif">
            <ul class="nav nav-collapse">
              <li
                class="{{ request()->routeIs('admin.withdraw.payment_method') && empty(request()->input('status')) ? 'active' : '' }}">
                <a href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Payment Methods') }}</span>
                </a>
              </li>

              <li
                class="{{ request()->routeIs('admin.withdraw.withdraw_request') && empty(request()->input('status')) ? 'active' : '' }}">
                <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Permintaan Penarikan') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- packages management --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && (in_array('Packages Management', $rolePermissions) || in_array('Package Management', $rolePermissions))))
        <li class="nav-item @if (request()->routeIs('admin.package.settings')) active 
            @elseif (request()->routeIs('admin.package.index')) active 
            @elseif (request()->routeIs('admin.package.edit')) active @endif">
          <a data-toggle="collapse" href="#packageManagement">
            <i class="fas fa-box-open"></i>
            <p>{{ __('Manajemen Paket') }}</p>
            <span class="caret"></span>
          </a>

          <div id="packageManagement" class="collapse 
              @if (request()->routeIs('admin.package.settings')) show 
              @elseif (request()->routeIs('admin.package.index')) show 
              @elseif (request()->routeIs('admin.package.edit')) show @endif">
            <ul class="nav nav-collapse">

              <li class="{{ request()->routeIs('admin.package.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.package.settings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Pengaturan') }}</span>
                </a>
              </li>
              <li class=" @if (request()->routeIs('admin.package.index')) active 
            @elseif (request()->routeIs('admin.package.edit')) active @endif">
                <a href="{{ route('admin.package.index', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Packages') }}</span>
                </a>
              </li>

            </ul>
          </div>
        </li>
        @endif

        {{-- menu builder --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Menu Builder', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.menu_builder')) active @endif">
          <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}">
            <i class="fas fa-bars"></i>
            <p>{{ __('Pembuat Menu') }}</p>
          </a>
        </li>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && (in_array('Pages', $rolePermissions) || in_array('Custom Pages', $rolePermissions))))
        <li class="nav-item
            @if (request()->routeIs([
                    'admin.pages.home_page.section_content',
                    'admin.pages.home_page.hero_section.slider_version',
                    'admin.pages.counter_section',
                    'admin.pages.about_us.counter_section',
                    'admin.pages.testimonial_section',
                    'admin.pages.home_page.product_section',
                    'admin.pages.home_page.section_customization',
                    'admin.pages.home_page.partners',
                    'admin.pages.faq_management',
                    'admin.pages.about_us.index',
                    'admin.pages.blog.categories',
                    'admin.pages.blog.blogs',
                    'admin.pages.blog.create_blog',
                    'admin.pages.blog.edit_blog',
                    'admin.pages.home_page.benifit_section',
                    'admin.pages.footer.logo_and_image',
                    'admin.pages.footer.content',
                    'admin.pages.footer.quick_links',
                    'admin.settings.seo',
                    'admin.pages.breadcrumb.image',
                    'admin.pages.breadcrumb.headings',
                    'admin.pages.additional_pages',
                    'admin.pages.additional_pages.create_page',
                    'admin.pages.feature_section',
                    'admin.pages.additional_pages.edit_page',
                    'admin.pages.about_us.customize',
                    'admin.additional_sections',
                    'admin.additional_section.create',
                    'admin.additional_section.edit',
                    'admin.pages.home_page.additional_sections',
                    'admin.pages.home_page.additional_section.create',
                    'admin.pages.home_page.additional_section.edit',
                    'admin.pages.contact_page',
                ])) active @endif">
          <a data-toggle="collapse" href="#pages">
            <i class="far fa-file-alt"></i>
            <p>{{ __('Halaman') }}</p>
            <span class="caret"></span>
          </a>
          <div id="pages" class="collapse
            @if (request()->routeIs([
                    'admin.pages.home_page.section_content',
                    'admin.pages.home_page.hero_section.slider_version',
                    'admin.pages.counter_section',
                    'admin.pages.about_us.counter_section',
                    'admin.pages.testimonial_section',
                    'admin.pages.home_page.product_section',
                    'admin.pages.home_page.section_customization',
                    'admin.pages.home_page.partners',
                    'admin.pages.faq_management',
                    'admin.pages.feature_section',
                    'admin.pages.about_us.index',
                    'admin.pages.blog.categories',
                    'admin.pages.blog.blogs',
                    'admin.pages.blog.create_blog',
                    'admin.pages.blog.edit_blog',
                    'admin.pages.home_page.benifit_section',
                    'admin.pages.footer.logo_and_image',
                    'admin.pages.footer.content',
                    'admin.pages.footer.quick_links',
                    'admin.settings.seo',
                    'admin.pages.breadcrumb.image',
                    'admin.pages.breadcrumb.headings',
                    'admin.pages.additional_pages',
                    'admin.pages.additional_pages.create_page',
                    'admin.pages.additional_pages.edit_page',
                    'admin.pages.about_us.customize',
                    'admin.additional_sections',
                    'admin.additional_section.create',
                    'admin.additional_section.edit',
                    'admin.pages.home_page.additional_sections',
                    'admin.pages.home_page.additional_section.create',
                    'admin.pages.home_page.additional_section.edit',
                    'admin.pages.contact_page',
                ])) show @endif">
            <ul class="nav
              nav-collapse">
              {{-- Home page --}}
              <li class="submenu">
                <a data-toggle="collapse" href="#home-page" aria-expanded="{{ request()->routeIs('admin.pages.home_page.section_content') ||
                    request()->routeIs('admin.pages.home_page.hero_section.slider_version') ||
                    request()->routeIs('admin.pages.counter_section') ||
                    request()->routeIs('admin.pages.home_page.product_section') ||
                    request()->routeIs('admin.pages.home_page.benifit_section') ||
                    request()->routeIs('admin.pages.home_page.additional_sections') ||
                    request()->routeIs('admin.pages.feature_section') ||
                    request()->routeIs('admin.pages.home_page.additional_section.edit') ||
                    request()->routeIs('admin.pages.home_page.additional_section.create') ||
                    request()->routeIs('admin.pages.home_page.section_customization') ||
                    request()->routeIs('admin.pages.home_page.partners')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Beranda') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="home-page" class="collapse
                    @if (request()->routeIs('admin.pages.home_page.section_content') ||
                            request()->routeIs('admin.pages.home_page.hero_section.slider_version') ||
                            request()->routeIs('admin.pages.counter_section') ||
                            request()->routeIs('admin.pages.home_page.product_section') ||
                            request()->routeIs('admin.pages.home_page.section_customization') ||
                            request()->routeIs('admin.pages.feature_section') ||
                            request()->routeIs('admin.pages.home_page.partners') ||
                            request()->routeIs('admin.pages.home_page.benifit_section') ||
                            request()->routeIs('admin.shop_management.create_product') ||
                            request()->routeIs('admin.pages.home_page.additional_sections') ||
                            request()->routeIs('admin.pages.home_page.additional_section.edit') ||
                            request()->routeIs('admin.pages.home_page.additional_section.create') ||
                            request()->routeIs('admin.shop_management.edit_product')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.pages.home_page.section_content') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.pages.home_page.section_content', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Images & Texts') }}</span>
                      </a>
                    </li>
                    <!-- additional sections -->
                    <li class="submenu">
                      <a data-toggle="collapse" href="#home-add-section" aria-expanded="{{ request()->routeIs('admin.pages.home_page.additional_sections') ||
                          request()->routeIs('admin.pages.home_page.additional_section.create') ||
                          request()->routeIs('admin.pages.home_page.additional_section.edit')
                              ? 'true'
                              : 'false' }}">
                        <span class="sub-item">{{ __('Additional Sections') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="home-add-section" class="collapse
                    @if (request()->routeIs('admin.pages.home_page.additional_sections') ||
                            request()->routeIs('admin.pages.home_page.additional_section.create') ||
                            request()->routeIs('admin.pages.home_page.additional_section.edit')) show @endif pl-3">
                        <ul class="nav nav-collapse subnav">
                          <li
                            class="{{ request()->routeIs('admin.pages.home_page.additional_section.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.home_page.additional_section.create') }}">
                              <span class="sub-item">{{ __('Add Section') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.pages.home_page.additional_sections') || request()->routeIs('admin.pages.home_page.additional_section.edit') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.pages.home_page.additional_sections', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Sections') }}
                              </span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>

                    @if ($settings->theme_version == 2)
                    <li
                      class="{{ request()->routeIs('admin.pages.home_page.hero_section.slider_version') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.pages.home_page.hero_section.slider_version', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Sliders') }}</span>
                      </a>
                    </li>
                    @endif

                    @if ($settings->theme_version == 3)
                    <li class="{{ request()->routeIs('admin.pages.home_page.benifit_section') ? 'active' : '' }}">
                      <a
                        href="{{ route('admin.pages.home_page.benifit_section', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Benifit Section') }}</span>
                      </a>
                    </li>
                    @endif

                    <li class="{{ request()->routeIs('admin.pages.home_page.section_customization') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.home_page.section_customization') }}">
                        <span class="sub-item">{{ __('Section Show/Hide') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- About page --}}
              <li class="submenu">
                <a data-toggle="collapse" href="#about-page" aria-expanded="{{ request()->routeIs('admin.pages.about_us.index') ||
                    request()->routeIs('admin.pages.about_us.customize') ||
                    request()->routeIs('admin.additional_sections') ||
                    request()->routeIs('admin.pages.about_us.counter_section') ||
                    request()->routeIs('admin.additional_section.create') ||
                    request()->routeIs('admin.additional_section.edit')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Tentang Kami') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="about-page" class="collapse
                    @if (request()->routeIs('admin.pages.about_us.index') ||
                            request()->routeIs('admin.about_us.customize') ||
                            request()->routeIs('admin.additional_sections') ||
                            request()->routeIs('admin.pages.about_us.counter_section') ||
                            request()->routeIs('admin.pages.about_us.customize') ||
                            request()->routeIs('admin.additional_section.create') ||
                            request()->routeIs('admin.additional_section.edit')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.pages.about_us.index') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.about_us.index', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('About') }}</span>
                      </a>
                    </li>

                    <!-- additional sections -->
                    <li class="submenu">
                      <a data-toggle="collapse" href="#addi-section" aria-expanded="{{ request()->routeIs('admin.additional_sections') ||
                          request()->routeIs('admin.additional_section.create') ||
                          request()->routeIs('admin.additional_section.edit')
                              ? 'true'
                              : 'false' }}">
                        <span class="sub-item">{{ __('Additional Sections') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="addi-section" class="collapse
                    @if (request()->routeIs('admin.additional_sections') ||
                            request()->routeIs('admin.additional_section.create') ||
                            request()->routeIs('admin.additional_section.edit')) show @endif pl-3">
                        <ul class="nav nav-collapse subnav">
                          <li class="{{ request()->routeIs('admin.additional_section.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.additional_section.create') }}">
                              <span class="sub-item">{{ __('Add Section') }}</span>
                            </a>
                          </li>
                          <li
                            class="{{ request()->routeIs('admin.additional_sections') || request()->routeIs('admin.additional_section.edit') ? 'active' : '' }}">
                            <a href="{{ route('admin.additional_sections', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Sections') }}
                              </span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>

                    @if ($settings->theme_version == 3)
                    <li class="{{ request()->routeIs('admin.pages.about_us.counter_section') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.about_us.counter_section', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Counters') }}</span>
                      </a>
                    </li>
                    @endif

                    <li class="{{ request()->routeIs('admin.pages.about_us.customize') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.about_us.customize', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Hide / Show Section') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>


              <li class="submenu">
                <a data-toggle="collapse" href="#commonsections" aria-expanded="{{ request()->routeIs('admin.pages.footer.content') ||
                    request()->routeIs('admin.pages.footer.logo_and_image') ||
                    request()->routeIs('admin.pages.counter_section') ||
                    request()->routeIs('admin.pages.testimonial_section') ||
                    request()->routeIs('admin.pages.footer.quick_links') ||
                    request()->routeIs('admin.pages.breadcrumb.image') ||
                    request()->routeIs('admin.pages.breadcrumb.headings') ||
                    request()->routeIs('admin.pages.feature_section')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Bagian Umum') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="commonsections" class="collapse 
                    @if (request()->routeIs('admin.pages.footer.content')) show 
                    @elseif (request()->routeIs('admin.pages.footer.logo_and_image')) show
                    @elseif (request()->routeIs('admin.pages.counter_section')) show
                    @elseif (request()->routeIs('admin.pages.footer.quick_links')) show
                    @elseif (request()->routeIs('admin.pages.breadcrumb.image')) show
                    @elseif (request()->routeIs('admin.pages.testimonial_section')) show
                    @elseif (request()->routeIs('admin.pages.breadcrumb.headings')) show
                    @elseif (request()->routeIs('admin.pages.feature_section')) show @endif">
                  <ul class="nav nav-collapse subnav">


                    <li class="{{ request()->routeIs('admin.pages.feature_section') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.feature_section', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Features') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.pages.testimonial_section') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.testimonial_section', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Testimonials') }}</span>
                      </a>
                    </li>
                    @if ($settings->theme_version != 3)
                    <li class="{{ request()->routeIs('admin.pages.counter_section') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.counter_section', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Counters') }}</span>
                      </a>
                    </li>
                    @endif

                    <li class="submenu">
                      <a data-toggle="collapse" href="#footer-common"
                        aria-expanded="{{ request()->routeIs('admin.pages.footer.quick_links') || request()->routeIs('admin.pages.footer.content') || request()->routeIs('admin.pages.footer.logo_and_image') ? 'true' : 'false' }}">
                        <span class="sub-item">{{ __('Footer') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="footer-common" class="collapse 
                    @if (request()->routeIs('admin.pages.footer.quick_links')) show 
                    @elseif (request()->routeIs('admin.pages.footer.content')) show
                    @elseif (request()->routeIs('admin.pages.footer.logo_and_image')) show @endif">
                        <ul class="nav nav-collapse subnav">
                          <li class="{{ request()->routeIs('admin.pages.footer.logo_and_image') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.footer.logo_and_image') }}">
                              <span class="sub-item">{{ __('Logo & Image') }}</span>
                            </a>
                          </li>

                          <li class="{{ request()->routeIs('admin.pages.footer.content') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.footer.content', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Content') }}</span>
                            </a>
                          </li>
                          <li class="{{ request()->routeIs('admin.pages.footer.quick_links') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.footer.quick_links', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Quick Links') }}</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li class="submenu">
                      <a data-toggle="collapse" href="#Breadcrumb"
                        aria-expanded="{{ request()->routeIs('admin.pages.breadcrumb.image') || request()->routeIs('admin.pages.breadcrumb.headings') ? 'true' : 'false' }}">
                        <span class="sub-item">{{ __('Breadcrumb') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div id="Breadcrumb" class="collapse 
                    @if (request()->routeIs('admin.pages.breadcrumb.image')) show 
                    @elseif (request()->routeIs('admin.pages.breadcrumb.headings')) show @endif">
                        <ul class="nav nav-collapse subnav">
                          <li class="{{ request()->routeIs('admin.pages.breadcrumb.image') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.breadcrumb.image') }}">
                              <span class="sub-item">{{ __('Image') }}</span>
                            </a>
                          </li>

                          <li class="{{ request()->routeIs('admin.pages.breadcrumb.headings') ? 'active' : '' }}">
                            <a
                              href="{{ route('admin.pages.breadcrumb.headings', ['language' => $defaultLang->code]) }}">
                              <span class="sub-item">{{ __('Headings') }}</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                  </ul>
                </div>
              </li>

              {{-- faq --}}
              <li class="{{ request()->routeIs('admin.pages.faq_management') ? 'active' : '' }}">
                <a href="{{ route('admin.pages.faq_management', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('FAQ') }}</span>
                </a>
              </li>
              {{-- Blog page --}}
              <li class="submenu">
                <a data-toggle="collapse" href="#blog-page" aria-expanded="{{ request()->routeIs('admin.pages.blog.categories') ||
                    request()->routeIs('admin.pages.blog.blogs') ||
                    request()->routeIs('admin.pages.blog.create_blog') ||
                    request()->routeIs('admin.pages.blog.edit_blog')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Tips & Cerita Wisata') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="blog-page" class="collapse
                    @if (request()->routeIs('admin.pages.blog.categories') ||
                            request()->routeIs('admin.pages.blog.create_blog') ||
                            request()->routeIs('admin.pages.blog.edit_blog') ||
                            request()->routeIs('admin.pages.blog.blogs')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.pages.blog.categories') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.blog.categories', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Kategori') }}</span>
                      </a>
                    </li>

                    <li
                      class="{{ request()->routeIs('admin.pages.blog.blogs') || request()->routeIs('admin.pages.blog.create_blog') || request()->routeIs('admin.pages.blog.edit_blog') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.blog.blogs', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Posts') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- contact us page --}}
              <li class="{{ request()->routeIs('admin.pages.contact_page') ? 'active' : '' }}">
                <a href="{{ route('admin.pages.contact_page') }}">
                  <span class="sub-item">{{ __('Halaman Kontak') }}</span>
                </a>
              </li>
              {{-- Additional Pages --}}
              <li class="submenu">
                <a data-toggle="collapse" href="#Additional-page" aria-expanded="{{ request()->routeIs('admin.pages.additional_pages') ||
                    request()->routeIs('admin.pages.additional_pages.create_page') ||
                    request()->routeIs('admin.pages.additional_pages.edit_page')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Halaman Tambahan') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="Additional-page" class="collapse
                    @if (request()->routeIs('admin.pages.additional_pages') ||
                            request()->routeIs('admin.pages.additional_pages.create_page') ||
                            request()->routeIs('admin.pages.additional_pages.edit_page')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li
                      class="{{ request()->routeIs('admin.pages.additional_pages') || request()->routeIs('admin.pages.additional_pages.edit_page') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.additional_pages', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Semua Halaman') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.pages.additional_pages.create_page') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.additional_pages.create_page') }}">
                        <span class="sub-item">{{ __('Add Page') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              {{-- <li class="submenu">
                <a data-toggle="collapse" href="#breadcrumb" aria-expanded="{{ request()->routeIs('admin.pages.breadcrumb.image') || request()->routeIs('admin.pages.breadcrumb.headings')
                        ? 'true'
                        : 'false' }}">
                  <span class="sub-item">{{ __('Breadcrumb') }}</span>
                  <span class="caret"></span>
                </a>
                <div id="breadcrumb"
                  class="collapse
                    @if (request()->routeIs('admin.pages.breadcrumb.image') || request()->routeIs('admin.pages.breadcrumb.headings')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.pages.breadcrumb.image') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.breadcrumb.image') }}">
                        <span class="sub-item">{{ __('Image') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.pages.breadcrumb.headings') ? 'active' : '' }}">
                      <a href="{{ route('admin.pages.breadcrumb.headings', ['language' => $defaultLang->code]) }}">
                        <span class="sub-item">{{ __('Headings') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li> --}}

              {{-- seo --}}
              <li class="{{ request()->routeIs('admin.settings.seo') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.seo', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Informasi SEO') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- Transaction --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transaction', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.transcation')) active @endif">
          <a href="{{ route('admin.transcation', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-exchange-alt"></i>
            <p>{{ __('Transaksi') }}</p>
          </a>
        </li>
        @endif

        {{-- Support Tickets --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Tickets', $rolePermissions)))
        <li
          class="nav-item @if (request()->routeIs('admin.support_ticket.setting')) active
            @elseif (request()->routeIs('admin.support_tickets')) active
            @elseif (request()->routeIs('admin.support_tickets.message')) active active
            @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif">
          <a data-toggle="collapse" href="#support_ticket">
            <i class="far fa-life-ring"></i>
            <p>{{ __('Tiket Bantuan') }}</p>
            <span class="caret"></span>
          </a>

          <div id="support_ticket" class="collapse
              @if (request()->routeIs('admin.support_ticket.setting')) show
              @elseif (request()->routeIs('admin.support_tickets')) show
              @elseif (request()->routeIs('admin.support_tickets.message')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.support_ticket.setting')) active @endif">
                <a href="{{ route('admin.support_ticket.setting') }}">
                  <span class="sub-item">{{ __('Pengaturan') }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets') }}">
                  <span class="sub-item">{{ __('Semua Tiket') }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 1 ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets', ['status' => 1]) }}">
                  <span class="sub-item">{{ __('Tiket Menunggu') }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 2 ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets', ['status' => 2]) }}">
                  <span class="sub-item">{{ __('Open Tickets') }}</span>
                </a>
              </li>
              <li
                class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 3 ? 'active' : '' }}">
                <a href="{{ route('admin.support_tickets', ['status' => 3]) }}">
                  <span class="sub-item">{{ __('Closed Tickets') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- advertise --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Advertisements', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.advertise.settings')) active 
            @elseif (request()->routeIs('admin.advertise.all_advertisement')) active @endif">
          <a data-toggle="collapse" href="#customid">
            <i class="fas fa-ad"></i>
            <p>{{ __('Iklan') }}</p>
            <span class="caret"></span>
          </a>

          <div id="customid" class="collapse @if (request()->routeIs('admin.advertise.settings')) show 
              @elseif (request()->routeIs('admin.advertise.all_advertisement')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.advertise.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.advertise.settings', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Pengaturan') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.advertise.all_advertisement') ? 'active' : '' }}">
                <a href="{{ route('admin.advertise.all_advertisement', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Semua Iklan') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- announcement popup --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Announcement Popups', $rolePermissions)))
        <li class="nav-item @if (request()->routeIs('admin.announcement_popups')) active 
            @elseif (request()->routeIs('admin.announcement_popups.select_popup_type')) active 
            @elseif (request()->routeIs('admin.announcement_popups.create_popup')) active 
            @elseif (request()->routeIs('admin.announcement_popups.edit_popup')) active @endif">
          <a href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
            <i class="fal fa-bullhorn"></i>
            <p>{{ __('Pengumuman Popup') }}</p>
          </a>
        </li>
        @endif

        {{-- Settings --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && (in_array('Settings', $rolePermissions) || in_array('Basic Settings', $rolePermissions))))
        <li class="nav-item 
            @if (request()->routeIs([
                    'admin.pages.contact_page',
                    'admin.settings.mail_from_admin',
                    'admin.settings.mail_to_admin',
                    'admin.settings.mail_templates',
                    'admin.settings.edit_mail_template',
                    'admin.settings.plugins',
                    'admin.settings.payment_gateways.online_gateways',
                    'admin.settings.payment_gateways.offline_gateways',
                    'admin.settings.maintenance_mode',
                    'admin.settings.general_settings',
                    'admin.settings.cookie_alert',
                    'admin.settings.social_medias',
                    'admin.settings.language_management',
                    'admin.settings.language_management.edit_front_keyword',
                    'admin.settings.language_management.edit_admin_keyword',
                ])) active @endif">
          <a data-toggle="collapse" href="#basic_settings">
            <i class="fas fa-cog"></i>
            <p>{{ __('Pengaturan') }}</p>
            <span class="caret"></span>
          </a>

          <div id="basic_settings" class="collapse 
              @if (request()->routeIs([
                      'admin.pages.contact_page',
                      'admin.settings.mail_from_admin',
                      'admin.settings.mail_to_admin',
                      'admin.settings.mail_templates',
                      'admin.settings.edit_mail_template',
                      'admin.settings.plugins',
                      'admin.settings.payment_gateways.online_gateways',
                      'admin.settings.payment_gateways.offline_gateways',
                      'admin.settings.maintenance_mode',
                      'admin.settings.cookie_alert',
                      'admin.settings.general_settings',
                      'admin.settings.social_medias',
                      'admin.settings.language_management',
                      'admin.settings.language_management.edit_front_keyword',
                      'admin.settings.language_management.edit_admin_keyword',
                  ])) show @endif
">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('admin.settings.general_settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.general_settings') }}">
                  <span class="sub-item">{{ __('Pengaturan Umum') }}</span>
                </a>
              </li>

              <li class="submenu">
                <a data-toggle="collapse" href="#mail-settings">
                  <span class="sub-item">{{ __('Pengaturan Email') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="mail-settings" class="collapse 
                    @if (request()->routeIs('admin.settings.mail_from_admin')) show 
                    @elseif (request()->routeIs('admin.settings.mail_to_admin')) show
                    @elseif (request()->routeIs('admin.settings.mail_templates')) show
                    @elseif (request()->routeIs('admin.settings.edit_mail_template')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li class="{{ request()->routeIs('admin.settings.mail_from_admin') ? 'active' : '' }}">
                      <a href="{{ route('admin.settings.mail_from_admin') }}">
                        <span class="sub-item">{{ __('Mail From Admin') }}</span>
                      </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.settings.mail_to_admin') ? 'active' : '' }}">
                      <a href="{{ route('admin.settings.mail_to_admin') }}">
                        <span class="sub-item">{{ __('Mail To Admin') }}</span>
                      </a>
                    </li>

                    <li class="@if (request()->routeIs('admin.settings.mail_templates')) active 
                        @elseif (request()->routeIs('admin.settings.edit_mail_template')) active @endif">
                      <a href="{{ route('admin.settings.mail_templates') }}">
                        <span class="sub-item">{{ __('Mail Templates') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              {{-- payment method --}}
              <li class="submenu">
                <a data-toggle="collapse" href="#payment-gateway"
                  aria-expanded="{{ request()->routeIs('admin.settings.payment_gateways.online_gateways') || request()->routeIs('admin.settings.payment_gateways.offline_gateways') ? 'true' : 'false' }}">
                  <span class="sub-item">{{ __('Metode Pembayaran') }}</span>
                  <span class="caret"></span>
                </a>

                <div id="payment-gateway" class="collapse
                    @if (request()->routeIs('admin.settings.payment_gateways.online_gateways')) show
                    @elseif (request()->routeIs('admin.settings.payment_gateways.offline_gateways')) show @endif">
                  <ul class="nav nav-collapse subnav">
                    <li
                      class="{{ request()->routeIs('admin.settings.payment_gateways.online_gateways') ? 'active' : '' }}">
                      <a href="{{ route('admin.settings.payment_gateways.online_gateways') }}">
                        <span class="sub-item">{{ __('Online Gateways') }}</span>
                      </a>
                    </li>

                    <li
                      class="{{ request()->routeIs('admin.settings.payment_gateways.offline_gateways') ? 'active' : '' }}">
                      <a href="{{ route('admin.settings.payment_gateways.offline_gateways') }}">
                        <span class="sub-item">{{ __('Offline Gateways') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              {{-- languages --}}
              <li class="@if (request()->routeIs('admin.settings.language_management')) active
            @elseif (request()->routeIs('admin.settings.language_management.edit_front_keyword')) active 
            @elseif (request()->routeIs('admin.settings.language_management.edit_admin_keyword')) active @endif">
                <a href="{{ route('admin.settings.language_management') }}">
                  <span class="sub-item">{{ __('Bahasa') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.settings.plugins') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.plugins') }}">
                  <span class="sub-item">{{ __('Plugin') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.settings.maintenance_mode') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.maintenance_mode') }}">
                  <span class="sub-item">{{ __('Mode Pemeliharaan') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.settings.cookie_alert') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.cookie_alert', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Cookie Alert') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('admin.settings.social_medias') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.social_medias') }}">
                  <span class="sub-item">{{ __('Social Medias') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        {{-- admin --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && (in_array('Staffs Management', $rolePermissions) || in_array('Admin Management', $rolePermissions))))
        <li class="nav-item @if (request()->routeIs('admin.admin_management.role_permissions')) active 
            @elseif (request()->routeIs('admin.admin_management.role.permissions')) active 
            @elseif (request()->routeIs('admin.admin_management.registered_admins')) active @endif">
          <a data-toggle="collapse" href="#admin">
            <i class="fas fa-user-cog"></i>
            <p>{{ __('Manajemen Staf') }}</p>
            <span class="caret"></span>
          </a>

          <div id="admin" class="collapse 
              @if (request()->routeIs('admin.admin_management.role_permissions')) show 
              @elseif (request()->routeIs('admin.admin_management.role.permissions')) show 
              @elseif (request()->routeIs('admin.admin_management.registered_admins')) show @endif">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('admin.admin_management.role_permissions')) active 
                  @elseif (request()->routeIs('admin.admin_management.role.permissions')) active @endif">
                <a href="{{ route('admin.admin_management.role_permissions') }}">
                  <span class="sub-item">{{ __('Role & Permissions') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('admin.admin_management.registered_admins') ? 'active' : '' }}">
                <a href="{{ route('admin.admin_management.registered_admins') }}">
                  <span class="sub-item">{{ __('Registered Admins') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif
      </ul>
    </div>
  </div>
</div>