@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h2 class="mb-2">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>

        <button type="submit" class="btn">Login</button>

        <p class="mt-2">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </form>
</div>
@endsection
