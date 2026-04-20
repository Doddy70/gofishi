<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Verifikasi Email Vendor') }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        {!! $body !!}
        <br>
        <p style="margin-top: 30px; font-size: 14px; color: #777;">
            {{ __('Jika Anda tidak melakukan pendaftaran ini, harap abaikan email ini.') }}<br>
            {{ __('Salam hangat,') }}<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>
    </div>
</body>
</html>
