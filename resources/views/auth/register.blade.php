@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h2 class="mb-2">Register</h2>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        
        <button type="submit" class="btn">Register</button>
        
        <p class="mt-2">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </form>
</div>
@endsection
