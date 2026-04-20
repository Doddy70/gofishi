<!DOCTYPE html>
<html>
<head>
    <title>Booking Accepted</title>
</head>
<body>
    <h2>Halo {{ $booking->booking_name }},</h2>
    <p>Kabar baik! Pemesanan Anda (Order No: <strong>{{ $booking->order_number }}</strong>) telah disetujui oleh Host.</p>
    <p>Silakan segera lakukan pembayaran melalui link pembayaran di dalam aplikasi kami, masuk ke menu Dasbor Pengguna Anda untuk melunasi transaksi.</p>
    <p>Detail Singkat:</p>
    <ul>
        <li>Tanggal Booking: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d-m-Y') }}</li>
        <li>Waktu Kumpul: {{ $booking->check_in_time }}</li>
        <li>Total Biaya yang harus dibayar: {{ $booking->currency_symbol }}{{ number_format($booking->grand_total, 2) }}</li>
    </ul>
    <br>
    <p>Terima kasih,</p>
    <p>Tim {{ env('APP_NAME') }}</p>
</body>
</html>
