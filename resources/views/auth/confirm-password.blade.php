@extends('layouts.auth-simple')

@section('content')
    <h1 class="title">Konfirmasi <em>Password</em></h1>

    <p class="subtitle">
        Demi keamanan akun, masukkan kembali password Anda untuk melanjutkan.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="group">
            <label class="label">Password</label>

            <input type="password" name="password" class="input" required autocomplete="current-password">

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn">
            Konfirmasi Password
        </button>
    </form>
@endsection
