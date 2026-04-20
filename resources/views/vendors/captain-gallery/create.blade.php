<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Tambah Foto Big Catch') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('vendor.captain_gallery.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Gambar*') }}</label>
            <div class="col-md-12 showImage mb-3">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail">
            </div>
            <input type="file" name="image" id="image" class="form-control">
            <p id="err_image" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Spesies Ikan') }}</label>
            <input type="text" class="form-control" name="title" placeholder="{{ __('Contoh: Giant Trevally') }}">
            <p id="err_title" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Berat (Opsional)') }}</label>
            <input type="text" class="form-control" name="weight" placeholder="{{ __('Contoh: 15 Kg') }}">
            <p id="err_weight" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Urutan*') }}</label>
            <input type="number" class="form-control" name="serial_number" value="0">
            <p id="err_serial_number" class="mt-2 mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Tutup') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Simpan') }}
        </button>
      </div>
    </div>
  </div>
</div>
