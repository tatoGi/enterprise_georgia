@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
<h2 class="mb-2">My Posts</h2>

@forelse($posts as $post)
    <div class="card">
        <div class="flex-between">
            <h3><a href="{{ route('posts.show', $post) }}" style="color: #2c3e50; text-decoration: none;">{{ $post->title }}</a></h3>
            <div>
                <span class="badge badge-{{ $post->category }}">{{ $post->category }}</span>
                <span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span>
            </div>
        </div>
        <p style="color: #666; font-size: 0.875rem; margin: 0.5rem 0;">
            Created {{ $post->created_at->diffForHumans() }}
        </p>
        <p>{{ Str::limit($post->description, 200) }}</p>
        <div class="flex">
            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">View</a>
            <a href="{{ route('posts.edit', $post) }}" class="btn">Edit</a>
        </div>
    </div>
@empty
    <div class="card text-center">
        <p>You haven't created any posts yet.</p>
        <a href="{{ route('posts.create') }}" class="btn">Create Your First Post</a>
    </div>
@endforelse

<div class="pagination">
    {{ $posts->links() }}
</div>
@endsection
