<!DOCTYPE html>
<html>
<head>
    <title>Payment Received</title>
</head>
<body>
    <h2>Halo {{ $booking->booking_name }},</h2>
    <p>Pembayaran Anda untuk Pemesanan (Order No: <strong>{{ $booking->order_number }}</strong>) telah kami terima dan transaksi selesai (Lunas).</p>
    <p>Ini adalah bukti bahwa Anda siap berlayar! Jangan lupa untuk datang tepat waktu di jam kumpul yang disepakati.</p>
    <p>Detail Pemesanan Anda:</p>
    <ul>
        <li>Tanggal Keberangkatan: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d-m-Y') }}</li>
        <li>Waktu Kumpul: {{ $booking->check_in_time }}</li>
        <li>Total Dibayar: {{ $booking->currency_symbol }}{{ number_format($booking->grand_total, 2) }}</li>
    </ul>
    <br>
    <p>Jika ada pertanyaan lebih lanjut, silakan hubungi kami atau Host Anda.</p>
    <br>
    <p>Terima kasih,</p>
    <p>Tim {{ env('APP_NAME') }}</p>
</body>
</html>
