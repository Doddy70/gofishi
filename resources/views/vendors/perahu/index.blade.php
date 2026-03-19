@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Perahu') }}</h4>
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
        <a href="#">{{ __('Perahu Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Perahu') }}</a>
      </li>
    </ul>
  </div>
  @php
    $vendor_id = Auth::guard('vendor')->user()->id;

    if ($vendor_id) {
        $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($vendor_id);

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
          <div class="row">
            <div class="col-xl-1">
              <div class="card-title d-inline-block">{{ __('Perahu') }}</div>
            </div>

            <div class="col-xl-8">
              <form action="{{ route('vendor.perahu_management.perahu') }}" method="get" id="roomSearchForm">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="mb-2">
                      <select name="roomCategories" id="" class="select2"
                        onchange="document.getElementById('roomSearchForm').submit()">
                        <option value="" selected disabled>{{ __('Category') }}</option>
                        <option value="All" {{ request()->input('roomCategories') == 'All' ? 'selected' : '' }}>
                          {{ __('All') }}</option>
                        @foreach ($roomCategories as $type)
                          <option @selected($type->name == request()->input('roomCategories')) value="{{ $type->name }}">{{ $type->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="mb-2">
                      <input type="text" name="title" value="{{ request()->input('title') }}" class="form-control"
                        placeholder="{{ __('Title') }}">
                    </div>
                  </div>
                  <input type="hidden" name="language" value="{{ request()->input('language') }}" class="form-control">
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <select name="featured" id="" class="select2"
                        onchange="document.getElementById('roomSearchForm').submit()">
                        <option value="" selected disabled>{{ __('Featured') }}</option>
                        <option value="All" {{ request()->input('featured') == 'All' ? 'selected' : '' }}>
                          {{ __('All') }}</option>
                        <option value="active" {{ request()->input('featured') == 'active' ? 'selected' : '' }}>
                          {{ __('Active') }}
                        </option>
                        <option value="pending" {{ request()->input('featured') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="unfeatured" {{ request()->input('featured') == 'unfeatured' ? 'selected' : '' }}>
                          {{ __('Not Featured') }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-xl-3">
              <div class="d-flex flex-wrap gap-10 mt-2 justify-content-xl-end">
                <a href="{{ route('vendor.perahu_management.create_perahu') }}" class="btn btn-primary btn-sm"><i
                    class="fas fa-plus"></i> {{ __('Add Perahu') }}</a>
                <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.perahu_management.bulk_delete.perahu') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($rooms) == 0)
                <h3 class="text-center">{{ __('NO PERAHU FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Featured Image') }}</th>
                        <th scope="col">{{ __('Title / KM Name') }}</th>
                        <th scope="col">{{ __('Captain') }}</th>
                        <th scope="col">{{ __('Engines') }}</th>
                        <th scope="col">{{ __('Additional Services') }}</th>
                        <th scope="col">{{ __('Packages') }}</th>
                        @if (count($charges) > 0)
                          <th scope="col">{{ __('Featured Status') }}</th>
                        @endif
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($rooms as $room)
                        @php
                          $room_content = $room->room_content->first();
                          if (is_null($room_content)) {
                              $room_content = App\Models\RoomContent::where('room_id', $room->id)
                                  ->where('language_id', $language->id)
                                  ->first();
                          }
                        @endphp
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $room->id }}">
                          </td>
                          <td>
                            @if (!empty($room_content))
                              <a href="{{ route('frontend.perahu.details', ['slug' => $room_content->slug, 'id' => $room->id]) }}"
                                target="_blank">
                                <div class="max-dimensions">
                                  <img
                                    src="{{ $room->feature_image ? asset('assets/img/perahu/featureImage/' . $room->feature_image) : asset('assets/admin/img/noimage.jpg') }}"
                                    alt="..." class="uploaded-img">
                                </div>
                              </a>
                            @else
                              <div class="max-dimensions">
                                <img
                                  src="{{ $room->feature_image ? asset('assets/img/perahu/featureImage/' . $room->feature_image) : asset('assets/admin/img/noimage.jpg') }}"
                                  alt="..." class="uploaded-img">
                              </div>
                            @endif
                          </td>
                          <td class="title">
                            @if (!empty($room_content))
                              <a href="{{ route('frontend.perahu.details', ['slug' => $room_content->slug, 'id' => $room->id]) }}"
                                target="_blank">
                                {{ strlen(@$room_content->title) > 50 ? mb_substr(@$room_content->title, 0, 50, 'utf-8') . '...' : @$room_content->title }}
                              </a>
                              <div class="text-xs text-neutral-500 font-bold mt-1">KM: {{ $room->nama_km ?? '--' }}</div>
                            @else
                              --
                            @endif
                          </td>

                          <td>{{ $room->kapten ?? '--' }}</td>
                          
                          <td>
                            <div class="text-xs">
                                <div>1: {{ $room->engine_1 ?? '--' }}</div>
                                @if($room->engine_2)
                                    <div>2: {{ $room->engine_2 }}</div>
                                @endif
                            </div>
                          </td>

                          <td>
                            <a
                              href="{{ route('vendor.perahu_management.manage_additional_service', ['id' => $room->id, 'language' => $defaultLang->code]) }}">
                              <button class="btn btn-primary btn-sm">{{ __('Manage') }}</button>
                            </a>
                          </td>
                          <td>
                            <a href="{{ route('vendor.perahu.packages.index', ['perahu_id' => $room->id]) }}" class="btn btn-info btn-sm">{{ __('Manage') }}</a>
                          </td>
                          @if (count($charges) > 0)
                            <td>
                              @php
                                $order_status = App\Models\RoomFeature::where('room_id', $room->id)->first();
                                $today_date = now()->format('Y-m-d');
                              @endphp

                              @if (is_null($order_status))
                                <button class="btn btn-primary featured btn-sm " data-toggle="modal"
                                  data-target="#featured" data-id="{{ $room->id }}"
                                  data-listing-id="{{ $room->id }}">
                                  {{ __('Pay to Feature') }}
                                </button>
                              @endif

                              @if ($order_status)
                                @if ($order_status->order_status == 'pending')
                                  <h2 class="d-inline-block"><span
                                      class="badge badge-warning">{{ __('pending') }}</span>
                                  </h2>
                                @endif
                                @if ($order_status->order_status == 'apporved')
                                  @if ($order_status->end_date < $today_date)
                                    <button class="btn btn-primary featured  btn-sm"
                                      data-toggle="modal"data-target="#featured"
                                      data-id="{{ $room->id }}">{{ __('Pay to Feature') }}</button>
                                  @else
                                    <h1 class="d-inline-block text-large"><span
                                        class="badge badge-success">{{ __('Active') }}</span>
                                    </h1>
                                  @endif
                                @endif
                                @if ($order_status->order_status == 'rejected')
                                  <button class="btn btn-primary featured btn-sm "
                                    data-toggle="modal"data-target="#featured"
                                    data-id="{{ $room->id }}">{{ __('Pay to Feature') }}</button>
                                @endif
                              @endif
                            </td>
                          @endif
                          <td>
                            @if (!empty($room_content))
                              @php
                                $roomCategories = App\Models\RoomCategory::where(
                                    'id',
                                    @$room->room_content[0]->room_category,
                                )->first();
                              @endphp
                              <a href="{{ route('frontend.perahu', ['category' => $roomCategories->slug]) }}"
                                target="_blank">{{ $roomCategories->name }}</a>
                            @else
                              --
                            @endif
                          </td>
                          <td>
                            <form id="StatusForm{{ $room->id }}" class="d-inline-block"
                              action="{{ route('vendor.perahu_management.update_perahu_status') }}" method="post">
                              @csrf
                              <input type="hidden" name="roomId" value="{{ $room->id }}">
                              <select
                                class="form-control {{ $room->status == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
                                name="status"
                                onchange="document.getElementById('StatusForm{{ $room->id }}').submit();">
                                <option value="1" {{ $room->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $room->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>

                              </select>
                            </form>
                          </td>

                          <td class="d-flex" style="gap: 5px;">
                            <a href="{{ route('vendor.perahu_management.edit_perahu', ['id' => $room->id]) }}" class="btn btn-warning btn-sm">
                              <i class="fas fa-edit"></i> {{ __('Edit') }}
                            </a>
                            <form class="deleteForm d-block" action="{{ route('vendor.perahu_management.delete_perahu', ['id' => $room->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
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
        <div class="card-footer">
          <div class="center">
            {{ $rooms->appends([
                    'title' => request()->input('title'),
                    'roomCategories' => request()->input('roomCategories'),
                    'language' => request()->input('language'),
                    'featured' => request()->input('featured'),
                ])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="razorPayForm"></div>
  @include('vendors.perahu.feature-payment')
@endsection
@section('script')
  @if ($midtrans['midtrans_mode'] == 1)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
  @else
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
  @endif
  <script src="https://js.stripe.com/v3/"></script>
  <script src="{{ $anetSource }}"></script>
  <script>
    let stripe_key = "{{ $stripe_key }}";
    let authorize_public_key = "{{ $anetClientKey }}";
    let authorize_login_key = "{{ $anetLoginId }}";
  </script>
  <script src="{{ asset('assets/admin/js/vendor-room-feature.js') }}"></script>
  <script>
    @if (old('gateway') == 'autorize.net')
      $(document).ready(function() {
        $('#stripe-element').removeClass('d-none');
      })
    @endif
  </script>
@endsection
