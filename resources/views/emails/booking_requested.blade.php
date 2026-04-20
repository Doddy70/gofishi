<!DOCTYPE html>
<html>
<head>
    <title>Booking Requested</title>
</head>
<body>
    <h2>Halo {{ $booking->booking_name }},</h2>
    <p>Terima kasih telah melakukan pemesanan (Order No: <strong>{{ $booking->order_number }}</strong>).</p>
    <p>Pemesanan Anda sedang menunggu persetujuan dari Host. Kami akan segera memberitahu Anda ketika Host telah merespons pesanan Anda.</p>
    <p>Detail Singkat:</p>
    <ul>
        <li>Tanggal Booking: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d-m-Y') }}</li>
        <li>Waktu Kumpul: {{ $booking->check_in_time }}</li>
        <li>Total Biaya: {{ $booking->currency_symbol }}{{ number_format($booking->grand_total, 2) }}</li>
    </ul>
    <br>
    <p>Salam,</p>
    <p>Tim {{ env('APP_NAME') }}</p>
</body>
</html>
