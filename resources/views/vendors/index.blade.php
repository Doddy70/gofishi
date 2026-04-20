@extends('vendors.layout')

@section('content')
  {{-- Modern Welcome Banner --}}
  <div class="welcome-banner animate__animated animate__fadeIn">
    <div class="welcome-text">
        <h2>{{ __('Good Morning') }}, <span style="color: var(--accent-red)">{{ Auth::guard('vendor')->user()->username }}!</span></h2>
        <p>Modern oceanic UI, premium boat rental business management.</p>
        <div class="mt-3">
            <a href="{{ route('vendor.perahu_management.create_perahu') }}" class="btn btn-primary btn-round px-4">
                <i class="fas fa-plus mr-2"></i> {{ __('Tambah Perahu Baru') }}
            </a>
        </div>
    </div>
    <div class="welcome-boat hidden-sm">
        <svg viewBox="0 0 200 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 70L40 90H160L180 70H20Z" fill="#1e293b"/>
            <path d="M45 70L55 40H145L155 70H45Z" fill="#3b82f6" fill-opacity="0.2"/>
            <rect x="70" y="20" width="10" height="50" fill="#64748b"/>
            <path d="M80 25L140 45V25H80Z" fill="#ef4444"/>
            <circle cx="60" cy="80" r="3" fill="white"/>
            <circle cx="100" cy="80" r="3" fill="white"/>
            <circle cx="140" cy="80" r="3" fill="white"/>
        </svg>
    </div>
  </div>

  {{-- Stats Summary --}}
  <div class="row mb-4">
    <div class="col-md-4">
        <div class="modern-stat-card">
            <div class="stat-icon-box" style="background: #fee2e2; color: #ef4444;">
                <i class="fas fa-wallet"></i>
            </div>
            <span class="stat-label">{{ __('Total Earnings') }}</span>
            <span class="stat-val">Rp {{ number_format(Auth::guard('vendor')->user()->amount, 0, ',', '.') }}</span>
            <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up mr-1"></i> 12% {{ __('this month') }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-stat-card">
            <div class="stat-icon-box" style="background: #dbeafe; color: #3b82f6;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span class="stat-label">{{ __('Active Bookings') }}</span>
            <span class="stat-val">89</span> {{-- Placeholder as we need total active bookings count --}}
            <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up mr-1"></i> 5% {{ __('vs last week') }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="modern-stat-card">
            <div class="stat-icon-box" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-ship"></i>
            </div>
            <span class="stat-label">{{ __('Total Boats') }}</span>
            <span class="stat-val">{{ $totalRooms }}</span>
            <div class="stat-trend trend-up">
                <i class="fas fa-anchor mr-1"></i> 3% {{ __('growth') }}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    {{-- Chart Section --}}
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title font-weight-bold">{{ __('Booking Trends') }}</h4>
          <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">Monthly</button>
          </div>
        </div>
        <div class="card-body">
          <div class="chart-container" style="height: 300px;">
            <canvas id="modernRevenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Active Bookings Table --}}
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
           <h4 class="card-title font-weight-bold">{{ __('Active Bookings Overview') }}</h4>
        </div>
        <div class="card-body">
            @if(count($recent_bookings) == 0)
                <p class="text-center text-muted my-4">{{ __('Belum ada pesanan terbaru.') }}</p>
            @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Booking ID') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_bookings as $booking)
                        <tr>
                            <td class="font-weight-bold">#{{ $booking->order_number }}</td>
                            <td>
                                @if($booking->payment_status == 1)
                                    <span class="badge badge-success">{{ __('Confirmed') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
      </div>
    </div>

    {{-- Full Recent Bookings Table --}}
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
               <h4 class="card-title font-weight-bold">{{ __('Detailed Recent Bookings') }}</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_bookings as $booking)
                            <tr>
                                <td>#{{ $booking->order_number }}</td>
                                <td>{{ $booking->user ? $booking->user->username : $booking->booking_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->arrival_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($booking->departure_date)->format('d M, Y') }}</td>
                                <td>
                                    @if($booking->payment_status == 1)
                                        <span class="badge badge-success">Confirmed</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>
  <script>
    "use strict";
    const monthArr = @php echo json_encode($monthArr) @endphp;
    const revenueArr = @php echo json_encode($revenueArr) @endphp;

    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('modernRevenueChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthArr,
                datasets: [{
                    label: 'Revenue',
                    data: revenueArr,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    });
  </script>
@endsection
