@extends('admin.layout')

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="pb-2">{{ __('Welcome back') . ',' }} {{ $authAdmin->first_name . ' ' . $authAdmin->last_name . '!' }}</h2>
  </div>

  <div class="row mb-3">
    <div class="col-lg-12">
      <div class="btn-group" role="group">
        <a href="{{ route('admin.dashboard', ['period' => 'daily']) }}" class="btn btn-sm btn-outline-primary {{ request()->input('period') == 'daily' ? 'active' : '' }}">{{ __('Today') }}</a>
        <a href="{{ route('admin.dashboard', ['period' => 'weekly']) }}" class="btn btn-sm btn-outline-primary {{ request()->input('period') == 'weekly' ? 'active' : '' }}">{{ __('This Week') }}</a>
        <a href="{{ route('admin.dashboard', ['period' => 'monthly']) }}" class="btn btn-sm btn-outline-primary {{ request()->input('period') == 'monthly' ? 'active' : '' }}">{{ __('This Month') }}</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary {{ !request()->filled('period') ? 'active' : '' }}">{{ __('All Time') }}</a>
      </div>
    </div>
  </div>

  {{-- dashboard information start --}}
  @php
    if (!is_null($roleInfo)) {
        $rolePermissions = json_decode($roleInfo->permissions);
    }
  @endphp

  <div class="row dashboard-items">
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transaction', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a class="card card-stats card-success card-round">
          <div class="card-body">
            <div class="row"> 
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Profit') }}</p>
                    Rp {{ number_format($dynamic_earning, 2, ',', '.') }}
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transaction', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a class="card card-stats card-primary card-round" href="{{ route('admin.transcation') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fal fa-exchange-alt"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Transaction') }}</p>
                  <h4 class="card-title">{{ $transcation_count }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Perahu Management', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.perahu_management.rooms', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-success card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-bed"></i>
                  </div>
                </div>
                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Perahu') }}</p>
                    <h4 class="card-title">{{ $totalRooms }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Log', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.subscription-log') }}">
          <div class="card card-stats card-primary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Subscription Log') }}</p>
                    <h4 class="card-title">{{ $payment_log }}
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.pages.blog.blogs', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-info card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-blog"></i>
                  </div>
                </div>
                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Blog') }}</p>
                    <h4 class="card-title">{{ $totalBlog }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.vendor_management.registered_vendor') }}">
          <div class="card card-stats card-secondary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-users"></i>
                  </div>
                </div>
                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Vendors') }}</p>
                    <h4 class="card-title">
                      {{ $vendors }}
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.user_management.registered_users') }}">
          <div class="card card-stats card-orchid card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="la flaticon-users"></i>
                  </div>
                </div>
                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Users') }}</p>
                    <h4 class="card-title">{{ $totalUser }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.user_management.subscribers') }}">
          <div class="card card-stats card-dark card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-bell"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Subscribers') }}</p>
                    <h4 class="card-title">{{ $totalSubscriber }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Packages Management', $rolePermissions)))
      <div class="col-xl-6 co-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title">{{ __('Monthly Package Purchase') }} ({{ date('Y') }})</div>
          </div>

          <div class="card-body">
            <div class="chart-container">
              <canvas id="packagePurchaseChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
      <div class="col-xl-6 co-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title">{{ __('Month wise registered users') }} ({{ date('Y') }})</div>
          </div>

          <div class="card-body">
            <div class="chart-container">
              <canvas id="userChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  {{-- dashboard information end --}}
@endsection

@section('script')
  {{-- chart js --}}
  <script type="text/javascript" src="{{ asset('assets/admin/js/chart.min.js') }}"></script>
  <script>
    "use strict";
    const monthArr = @php echo json_encode($monthArr) @endphp;
    const packagePurchaseIncomesArr = @php echo json_encode($packagePurchaseIncomesArr) @endphp;
    const totalUsersArr = @php echo json_encode($totalUsersArr) @endphp;
  </script>

  <script type="text/javascript" src="{{ asset('assets/admin/js/chart.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/chart-init.js') }}"></script>
@endsection
