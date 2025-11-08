@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="card text-center" style="max-width: 600px; margin: 3rem auto;">
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: #2c3e50;">Welcome to Blog Platform</h1>
    <p style="font-size: 1.125rem; color: #666; margin-bottom: 2rem;">
        Share your stories, ideas, and thoughts with the world!
    </p>

    <div class="flex" style="justify-content: center; gap: 1rem; margin-bottom: 3rem;">
        @guest
            <a href="{{ route('register') }}" class="btn" style="font-size: 1.125rem; padding: 0.75rem 2rem;">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-secondary" style="font-size: 1.125rem; padding: 0.75rem 2rem;">Login</a>
        @else
            <a href="{{ route('posts.create') }}" class="btn" style="font-size: 1.125rem; padding: 0.75rem 2rem;">Create Post</a>
        @endguest
        <a href="{{ route('posts.index') }}" class="btn btn-secondary" style="font-size: 1.125rem; padding: 0.75rem 2rem;">Browse Posts</a>
    </div>

    <div style="text-align: left; margin-top: 3rem;">
        <h3 style="margin-bottom: 1rem;">Features:</h3>
        <ul style="list-style: disc; margin-left: 2rem; color: #666;">
            <li>Create and publish blog posts with photos</li>
            <li>Organize posts by categories</li>
            <li>Comment and reply to discussions</li>
            <li>Moderation system for content quality</li>
            <li>User, Moderator, and Admin roles</li>
        </ul>
    </div>
</div>
@endsection
