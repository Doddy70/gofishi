<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $websiteInfo->website_title }}</title>
  <style>
    #pay-button {
      display: none
    }
  </style>
</head>

<body>
  <button class="btn btn-primary" id="pay-button">Pay Now</button>
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  @php 
    $mMode = $data['midtrans_mode'] ?? ($data['is_production'] ?? 1); // 1 = sandbox usually in some systems, but let's check
    // Actually our controller says isProd = (info['midtrans_mode'] == 0)
    // So if midtrans_mode == 0, it is PROD. Else Sandbox.
    $isSandbox = ($data['midtrans_mode'] != 0);
  @endphp

  @if ($isSandbox)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $data['client_key'] }}">
    </script>
  @else
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $data['client_key'] }}"></script>
  @endif
  <script>
    var baseUrl = "{{ route('index') }}";
    const payButton = document.querySelector('#pay-button');
    payButton.addEventListener('click', function(e) {
      e.preventDefault();

      snap.pay('{{ $snapToken }}', {
        // Optional
        onSuccess: function(result) {
          window.location.href = baseUrl + "/perahu/perahu-booking/midtrans/notify";

        },
        // Optionall
        onPending: function(result) {
          window.location.href = baseUrl + "/midtrans/cancel";
        },
        // Optional
        onError: function(result) {

          window.location.href = baseUrl + "/midtrans/cancel";

        }
      });
    });
    payButton.click()
  </script>
</body>

</html>
