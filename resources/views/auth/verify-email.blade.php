<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email — {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/help-desk.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/auth/auth.css'])
</head>

<body>

    <div class="card">

        <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M4 4h16v16H4z" />
                <path d="M22 6l-10 7L2 6" />
            </svg>
        </div>

        <h1 class="title">
            Verifikasi <em>Email</em>
        </h1>

        <p class="subtitle">
            Kami telah mengirimkan link verifikasi ke email Anda.
            Silakan cek inbox atau folder spam untuk melanjutkan.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="success">
                Link verifikasi baru berhasil dikirim ke email Anda.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit">
                    Keluar dari akun
                </button>
            </form>
        </div>

    </div>

</body>

</html>
