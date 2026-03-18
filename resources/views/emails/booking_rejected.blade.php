<!DOCTYPE html>
<html>
<head>
    <title>Booking Rejected</title>
</head>
<body>
    <h2>Halo {{ $booking->booking_name }},</h2>
    <p>Mohon maaf, pemesanan Anda (Order No: <strong>{{ $booking->order_number }}</strong>) tidak dapat disetujui oleh Host pada saat ini karena alasan jadwal atau ketersediaan.</p>
    <p>Silakan cari alternatif perahu lain yang tersedia di platform kami.</p>
    <br>
    <p>Terima kasih atas pengertiannya,</p>
    <p>Tim {{ env('APP_NAME') }}</p>
</body>
</html>
