@if (count($room_contents) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pb-20">
        @foreach ($room_contents as $room)
            @include('frontend.perahu._card', ['room' => $room])
        @endforeach
    </div>
    <div class="row mt-4">
        <div class="col-12 text-center">
            {{ $room_contents->appends(request()->input())->links() }}
        </div>
    </div>
@else
    <div class="text-center py-5">
        <x-lucide-anchor class="w-16 h-16 text-muted mb-3 opacity-20" />
        <h4 class="text-muted">Tidak ada armada ditemukan</h4>
        <p class="text-secondary">Coba ubah filter atau cari di lokasi lain</p>
    </div>
@endif
