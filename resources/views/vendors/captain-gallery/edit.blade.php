<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Foto Big Catch') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('vendor.captain_gallery.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" id="in_id">
          <div class="form-group">
            <label for="">{{ __('Gambar') }}</label>
            <div class="col-md-12 showImage mb-3">
              <img src="" alt="..." class="img-thumbnail in_image">
            </div>
            <input type="file" name="image" class="form-control">
            <p id="editErr_image" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Spesies Ikan') }}</label>
            <input type="text" class="form-control" name="title" id="in_title">
            <p id="editErr_title" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Berat (Opsional)') }}</label>
            <input type="text" class="form-control" name="weight" id="in_weight">
            <p id="editErr_weight" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Urutan*') }}</label>
            <input type="number" class="form-control" name="serial_number" id="in_serial_number">
            <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Tutup') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Perbarui') }}
        </button>
      </div>
    </div>
  </div>
</div>
