@extends('layouts.app')

@section('title', 'Blog Platform - Latest Posts')

@section('content')
@if(request()->routeIs('home'))
<div class="card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-bottom: 2rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Welcome to Blog Platform</h1>
    <p style="font-size: 1.125rem; opacity: 0.95;">Discover stories, ideas, and perspectives from our community</p>
</div>
@endif

<div class="flex-between mb-2">
    <h2>{{ request()->routeIs('home') ? 'Latest Posts' : 'All Posts' }}</h2>
    <form method="GET" action="{{ request()->routeIs('home') ? route('home') : route('posts.index') }}">
        <select name="category" onchange="this.form.submit()" style="padding: 0.5rem;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </form>
</div>

@forelse($posts as $post)
    <div class="card">
        <h3 style="margin-bottom: 0.5rem;">
            <a href="{{ route('posts.show', $post) }}" style="color: #2c3e50; text-decoration: none;">{{ $post->title }}</a>
        </h3>

        @if($post->photo)
            <img src="{{ asset('storage/' . $post->photo) }}" alt="{{ $post->title }}" style="max-width: 100%; max-height: 400px; object-fit: cover; margin: 1rem 0; border-radius: 5px;">
        @endif

        <div style="color: #666; font-size: 0.875rem; margin: 0.75rem 0; display: flex; gap: 1rem; align-items: center;">
            <span><strong>Author:</strong> {{ $post->user->name }}</span>
            <span>•</span>
            <span>{{ $post->created_at->format('F j, Y') }}</span>
            <span>•</span>
            <span><strong>Category:</strong> {{ $post->category }}</span>
            <span>•</span>
            <span>
                <strong>💬 {{ $post->comments_count }}</strong>
                {{ $post->comments_count === 1 ? 'comment' : 'comments' }}
            </span>
        </div>

        <p style="margin: 1rem 0;">{{ Str::limit($post->description, 300) }}</p>

        <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Read Full Post</a>
    </div>
@empty
    <div class="card text-center">
        <p>No posts found.</p>
    </div>
@endforelse

<div class="pagination">
    {{ $posts->links() }}
</div>
@endsection
