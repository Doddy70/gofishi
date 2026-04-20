@extends('admin.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Moderasi Ulasan') }}</h4>
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
            <a href="#">{{ __('Moderasi Ulasan') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-title d-inline-block">{{ __('Daftar Ulasan Penumpang') }}</div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($reviews) == 0)
                        <h3 class="text-center">{{ __('NO REVIEWS FOUND') . '!' }}</h3>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Reservasi') }}</th>
                                        <th scope="col">{{ __('Vendor (Host)') }}</th>
                                        <th scope="col">{{ __('Pengguna (Guest)') }}</th>
                                        <th scope="col">{{ __('Rating') }}</th>
                                        <th scope="col">{{ __('Ulasan') }}</th>
                                        <th scope="col">{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                    <tr>
                                        <td>#{{ $review->booking->order_number ?? $review->booking_id }}</td>
                                        <td>{{ $review->vendor->username ?? 'Vendor' }}</td>
                                        <td>{{ $review->user->username ?? 'User' }}</td>
                                        <td>{{ $review->rating }} / 5</td>
                                        <td>{{ mb_strimwidth($review->review_text, 0, 50, '...') }}</td>
                                        <td>
                                            <form class="deleteForm d-inline-block"
                                                action="{{ route('admin.user_reviews.delete', $review->id) }}"
                                                method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm BtnDelete">
                                                    <span class="btn-label">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    {{ __('Hapus') }}
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
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
@endsection