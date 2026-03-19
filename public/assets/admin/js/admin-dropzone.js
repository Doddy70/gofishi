(function ($) {
  "use strict";

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  // Dropzone initialization
  Dropzone.options.myDropzone = {
    url: typeof storeUrl !== 'undefined' ? storeUrl : '#',
    acceptedFiles: '.png, .jpg, .jpeg, .webp',
    maxFiles: typeof galleryImages !== 'undefined' ? (galleryImages > 10 ? 10 : galleryImages) : 10,
    success: function (file, response) {
      if (response.status == "success") {
        $("#sliders").append(`<input type="hidden" name="slider_images[]" id="slider${response.file_id}" value="${response.file_id}">`);

        var removeButton = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");
        var _this = this;
        removeButton.addEventListener("click", function (e) {
          e.preventDefault();
          e.stopPropagation();
          _this.removeFile(file);
          rmvimg(response.file_id);
        });

        file.previewElement.appendChild(removeButton);

        if (typeof response.error != 'undefined') {
          if (typeof response.file != 'undefined') {
            document.getElementById('errpreimg').innerHTML = response.file[0];
          }
        }
      } else {
        // Handle server-side errors returned as status != success
         if (response.errors) {
            bootnotify(response.errors[0], 'Error', 'danger');
         }
      }
    },
    error: function(file, message) {
       // Stop the spinner if there's an error
       $(file.previewElement).find('.dz-error-message').text(message);
       bootnotify(message, 'Error', 'danger');
    }
  };

  function rmvimg(fileid) {
    // you can do the AJAX request here.
    $(".request-loader").addClass("show");

    $.ajax({
      url: removeUrl,
      type: 'POST',
      data: {
        fileid: fileid
      },
      success: function (data) {
        $(".request-loader").removeClass("show");
        $("#slider" + fileid).remove();
      },
      error: function() {
        $(".request-loader").removeClass("show");
      }
    });

  }

  //remove existing images
  $(document).on('click', '.rmvbtndb', function () {
    let indb = $(this).data('indb');
    $(".request-loader").addClass("show");
    $.ajax({
      url: rmvdbUrl,
      type: 'POST',
      data: {
        fileid: indb
      },
      success: function (data) {
        $(".request-loader").removeClass("show");
          if (data.status == 'success') {
            location.reload();
          }
      },
      error: function() {
        $(".request-loader").removeClass("show");
      }
    });
  });
})(jQuery);
