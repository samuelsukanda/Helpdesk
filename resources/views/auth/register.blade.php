<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar — {{ config('app.name') }}</title>
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

        <h1 class="title">
            Buat <em>Akun</em>
        </h1>

        <p class="subtitle">
            Daftarkan akun baru untuk mulai menggunakan sistem.
        </p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="group">
                <label class="label">Nama Lengkap</label>

                <input type="text" name="name" class="input" value="{{ old('name') }}" required autofocus
                    autocomplete="name">

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="group">
                <label class="label">Alamat Email</label>

                <input type="email" name="email" class="input" value="{{ old('email') }}" required
                    autocomplete="username">

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="group">
                <label class="label">Password</label>

                <input type="password" name="password" class="input" required autocomplete="new-password">

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="group">
                <label class="label">Konfirmasi Password</label>

                <input type="password" name="password_confirmation" class="input" required autocomplete="new-password">

                @error('password_confirmation')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn">
                Daftar Sekarang
            </button>

            <div class="login">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk disini</a>
            </div>

        </form>

    </div>

</body>

</html>
