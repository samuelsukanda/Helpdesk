<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/help-desk.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/auth/auth.css'])
</head>

<body>

    <div class="login-wrap">

        {{-- Right form --}}
        <main class="login-main">
            <div class="login-form-wrap">

                <h1 class="form-title">Masuk ke akun <em>Anda</em></h1>
                <p class="form-subtitle">Gunakan email dan kata sandi untuk melanjutkan</p>

                @if (session('status'))
                    <div class="form-status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label" for="email">Alamat email</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="M2 7l10 7 10-7" />
                            </svg>
                            <input id="email" type="email" name="email"
                                class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                                placeholder="Email Anda" value="{{ old('email') }}" required autofocus
                                autocomplete="username" />
                        </div>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label class="form-label" for="password">Kata sandi</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input id="password" type="password" name="password"
                                class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                                placeholder="••••••••" required autocomplete="current-password" />
                        </div>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Options --}}
                    <div class="form-options">
                        <label class="remember-label" for="remember_me">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        Masuk sekarang
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>

</html>
