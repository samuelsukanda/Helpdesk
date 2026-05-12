<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password — {{ config('app.name') }}</title>
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

        <h1 class="title">Lupa <em>Password</em></h1>

        <p class="subtitle">
            Masukkan email akun Anda dan kami akan mengirimkan link untuk reset password.
        </p>

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="group">
                <label class="label" for="email">Alamat Email</label>

                <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required
                    autofocus>

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn" type="submit">
                Kirim Link Reset Password
            </button>

            <div class="back">
                <a href="{{ route('login') }}">Kembali ke login</a>
            </div>
        </form>

    </div>

</body>

</html>
