<script>
  'use strict';
  const baseURL = "{{ url('/') }}";
  const read_more = "Read More";
  const read_less = "Read Less";
  const show_more = "{{ __('Show More') . '+' }}";
  const show_less = "{{ __('Show Less') . '-' }}";
  var vapid_public_key = "{!! env('VAPID_PUBLIC_KEY') !!}";
  var googleApiStatus = {{ $basicInfo->google_map_api_key_status }};
  @if ($basicInfo->time_format == 24)
    var timePicker = true;
    var timeFormate = "HH:mm";
  @elseif ($basicInfo->time_format == 12)
    var timePicker = false;
    var timeFormate = "hh:mm A";
  @endif
</script>

<!-- Jquery JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/front/js/vendors/bootstrap.min.js') }}"></script>
<!-- Date-range Picker JS -->
<script src="{{ asset('assets/front/js/vendors/moment.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/daterangepicker.js') }}"></script>
<!-- Data Tables JS -->
<script src="{{ asset('assets/front/js/vendors/datatables.min.js') }}"></script>
<!-- Noui Range Slider JS -->
<script src="{{ asset('assets/front/js/vendors/nouislider.min.js') }}"></script>
<!-- Counter JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.counterup.min.js') }}"></script>
<!-- Nice Select JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.nice-select.min.js') }}"></script>
<!-- Select 2 JS -->
<script src="{{ asset('assets/front/js/vendors/select2.min.js') }}"></script>
<!-- Magnific Popup JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.magnific-popup.min.js') }}"></script>
<!-- Swiper Slider JS -->
<script src="{{ asset('assets/front/js/vendors/swiper-bundle.min.js') }}"></script>
<!-- Lazysizes -->
<script src="{{ asset('assets/front/js/vendors/lazysizes.min.js') }}"></script>
<!-- SVG Loader -->
<script src="{{ asset('assets/front/js/vendors/svg-loader.min.js') }}"></script>
{{-- whatsapp js --}}
<script src="{{ asset('assets/front/js/floating-whatsapp.js') }}"></script>
<!-- AOS JS -->
<script src="{{ asset('assets/front/js/vendors/aos.min.js') }}"></script>
<!-- Mouse Hover JS -->
<script src="{{ asset('assets/front/js/vendors/mouse-hover-move.js') }}"></script>
<!-- Leaflet Map JS -->
<script src="{{ asset('assets/front/js/vendors/leaflet.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/leaflet.markercluster.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/leaflet.fullscreen.min.js') }}"></script>
{{-- toastr --}}
<script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>

<!-- Syotimer script JS -->
<script src="{{ asset('assets/front/js/jquery-syotimer.min.js') }}"></script>

{{-- push notification js (Temporarily disabled to fix permission error)
<script src="{{ asset('assets/front/js/push-notification.js') }}"></script>
--}}

<!-- Main script JS -->
<script src="{{ asset('assets/front/js/script.js') }}"></script>

{{-- custom main js --}}
<script src="{{ asset('assets/front/js/main.js') }}"></script>

{{-- POV FEATURES LOGIC --}}
<script>
  "use strict";

  // POV FEATURE: MASTER-CARD CAROUSEL
  document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
      const btn = e.target.closest('.magic-nav-btn');
      if (!btn) return;
      
      e.preventDefault();
      e.stopPropagation();

      const card = btn.closest('.airbnb-master-card');
      if(!card) return;
      const imgs = card.querySelectorAll('.card-img-slide');
      const dots = card.querySelectorAll('.magic-dot');
      let activeIdx = Array.from(imgs).findIndex(img => img.classList.contains('active'));

      if(activeIdx === -1) activeIdx = 0;

      imgs[activeIdx].classList.remove('active');
      if(dots[activeIdx]) dots[activeIdx].classList.remove('active');

      if (btn.classList.contains('next')) {
        activeIdx = (activeIdx + 1) % imgs.length;
      } else {
        activeIdx = (activeIdx - 1 + imgs.length) % imgs.length;
      }

      imgs[activeIdx].classList.add('active');
      if(dots[activeIdx]) dots[activeIdx].classList.add('active');
    });
  });

  // POV FEATURE: SUPER SEARCH LOGIC
  function expandSearch() {
    const overlay = document.getElementById('searchOverlay');
    const header = document.getElementById('mainHeader');
    if(overlay) overlay.classList.add('active');
    if(header) header.classList.add('expanded');
    document.body.style.overflow = 'hidden'; 
  }

  function closeSearch(e) {
    const overlay = document.getElementById('searchOverlay');
    const header = document.getElementById('mainHeader');
    if(overlay) overlay.classList.remove('active');
    if(header) header.classList.remove('expanded');
    document.body.style.overflow = '';
    document.querySelectorAll('.search-segment').forEach(s => s.classList.remove('active'));
  }

  function focusInput(type) {
    document.querySelectorAll('.search-segment').forEach(s => s.classList.remove('active'));
    if (type === 'dest') {
      const segment = document.querySelector('.search-segment.first');
      if(segment) segment.classList.add('active');
      const input = document.getElementById('destInput');
      if(input) input.focus();
    } else if (type === 'date') {
      const segments = document.querySelectorAll('.search-segment');
      if(segments[1]) segments[1].classList.add('active');
      const dateInput = $('#dateInput');
      if(dateInput.length) dateInput.click();
    } else if (type === 'guests') {
      const segment = document.querySelector('.search-segment.last');
      if(segment) segment.classList.add('active');
    }
  }

  function scrollCategories(amount) {
    const container = document.getElementById('categoryScroll');
    if (container) {
      container.scrollBy({ left: amount, behavior: 'smooth' });
    }
  }
</script>

{{-- whatsapp init --}}
@if ($basicInfo->whatsapp_status == 1)
  <script type="text/javascript">
    $(function() {
      if($('#WAButton').length) {
        $('#WAButton').floatingWhatsApp({
          phone: "{{ $basicInfo->whatsapp_number }}",
          headerTitle: "{{ $basicInfo->whatsapp_header_title }}",
          popupMessage: `{!! nl2br($basicInfo->whatsapp_popup_message) !!}`,
          showPopup: "{{ $basicInfo->whatsapp_popup_status }}" == 1 ? true : false,
          buttonImage: '<img src="{{ asset('assets/img/whatsapp.svg') }}" />',
          position: "left"
        });
      }
    });
  </script>
@endif

<!--Start of Tawk.to Script-->
@if ($basicInfo->tawkto_status)
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = "{{ $basicInfo->tawkto_direct_chat_link }}";
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
@endif

@yield('script')

@if (session()->has('success'))
  <script>toastr['success']("{{ __(session('success')) }}");</script>
@endif
@if (session()->has('error'))
  <script>toastr['error']("{{ __(session('error')) }}");</script>
@endif
