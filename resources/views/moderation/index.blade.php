@extends('layouts.app')

@section('title', 'Moderation Queue')

@section('content')
<h2 class="mb-2">Moderation Queue - Pending Posts</h2>

@forelse($posts as $post)
    <div class="card">
        <div class="flex-between">
            <div>
                <h3>{{ $post->title }}</h3>
                <p style="color: #666; font-size: 0.875rem;">
                    By {{ $post->user->name }} | {{ $post->created_at->diffForHumans() }} |
                    <span class="badge badge-{{ $post->category }}">{{ $post->category }}</span>
                </p>
            </div>
            <span class="badge badge-pending">Pending</span>
        </div>

        @if($post->photo)
            <img src="{{ asset('storage/' . $post->photo) }}" alt="{{ $post->title }}" style="max-width: 200px; height: auto; margin: 0.5rem 0;">
        @endif

        <p>{{ Str::limit($post->description, 300) }}</p>

        <div class="flex">
            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">View Full Post</a>
            <form method="POST" action="{{ route('moderation.approve', $post) }}">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
            </form>
            <form method="POST" action="{{ route('moderation.reject', $post) }}" onsubmit="return confirm('Reject this post?')">
                @csrf
                <button type="submit" class="btn btn-danger">Reject</button>
            </form>
        </div>
    </div>
@empty
    <div class="card text-center">
        <p>No posts pending moderation.</p>
    </div>
@endforelse

<div class="pagination">
    {{ $posts->links() }}
</div>
@endsection
