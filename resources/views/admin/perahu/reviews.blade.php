@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Boat Reviews') }}</h4>
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
        <a href="#">{{ __('Perahu Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Reviews') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Reviews') }}</div>
            </div>
            <div class="col-lg-8">
              <form action="{{ route('admin.perahu_management.reviews') }}" method="GET" class="float-right">
                <input type="text" name="order_number" class="form-control" placeholder="Search by Order #" value="{{ request()->input('order_number') }}">
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($reviews) == 0)
                <h3 class="text-center">{{ __('NO REVIEW FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('User') }}</th>
                        <th scope="col">{{ __('Perahu') }}</th>
                        <th scope="col">{{ __('Rating') }}</th>
                        <th scope="col">{{ __('Comment') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reviews as $review)
                        <tr>
                          <td>{{ $review->userInfo ? $review->userInfo->username : 'Guest' }}</td>
                          <td>
                            @if($review->hotelRoom && $review->hotelRoom->room_content->first())
                              {{ $review->hotelRoom->room_content->first()->title }}
                            @else
                              ---
                            @endif
                          </td>
                          <td>
                            @for ($i = 1; $i <= 5; $i++)
                              <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning"></i>
                            @endfor
                          </td>
                          <td>{{ Str::limit($review->review, 50) }}</td>
                          <td>
                            <form class="deleteForm d-inline-block" action="{{ route('admin.perahu_management.review.delete', $review->id) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
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
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $reviews->appends(['order_number' => request()->input('order_number')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
