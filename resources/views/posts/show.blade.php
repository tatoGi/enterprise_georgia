@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="card">
    <h1 style="margin-bottom: 1rem;">{{ $post->title }}</h1>

    <div style="color: #666; font-size: 0.875rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e0e0e0;">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <span><strong>Author:</strong> {{ $post->user->name }}</span>
            <span>•</span>
            <span><strong>Published:</strong> {{ $post->created_at->format('F j, Y \a\t g:i A') }}</span>
            <span>•</span>
            <span><strong>Category:</strong> <span class="badge badge-approved">{{ $post->category }}</span></span>
            @if(auth()->check() && auth()->id() === $post->user_id)
                <span>•</span>
                <span><strong>Status:</strong> <span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span></span>
            @endif
            <span>•</span>
            <span><strong>💬 {{ $post->comments->count() }}</strong> {{ $post->comments->count() === 1 ? 'comment' : 'comments' }}</span>
        </div>
    </div>

    @if($post->photo)
        <img src="{{ asset('storage/' . $post->photo) }}" alt="{{ $post->title }}" class="post-image" style="width: 100%; max-height: 500px; object-fit: cover;">
    @endif

    <div style="margin: 1.5rem 0; font-size: 1.05rem; line-height: 1.7; white-space: pre-wrap;">{{ $post->description }}</div>

    @auth
        @if(auth()->id() === $post->user_id)
            <div class="flex">
                <a href="{{ route('posts.edit', $post) }}" class="btn">Edit</a>
                <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endif

        @if(auth()->user()->isAdmin() && auth()->id() !== $post->user_id)
            <form method="POST" action="{{ route('admin.posts.delete', $post) }}" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete (Admin)</button>
            </form>
        @endif
    @endauth
</div>

<!-- Comments Section -->
<div class="card">
    <h3 style="margin-bottom: 1rem;">Comments ({{ $post->comments->count() }})</h3>

    @auth
        <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-2" style="background: #f8f9fa; padding: 1rem; border-radius: 5px;">
            @csrf
            <div class="form-group">
                <textarea name="content" placeholder="Write a comment..." required style="min-height: 100px;"></textarea>
            </div>
            <button type="submit" class="btn">Post Comment</button>
        </form>
    @else
        <p style="background: #f8f9fa; padding: 1rem; border-radius: 5px; text-align: center;">
            <a href="{{ route('login') }}" style="color: #3498db; font-weight: bold;">Login</a> to join the conversation.
        </p>
    @endauth

    <div style="margin-top: 1.5rem;">
        @forelse($post->comments->whereNull('parent_id') as $comment)
        <div class="comment">
            <div class="comment-meta">
                <strong>{{ $comment->user->name }}</strong> | {{ $comment->created_at->diffForHumans() }}
                @if($comment->is_edited)
                    <em>(edited)</em>
                @endif
            </div>
            <p>{{ $comment->content }}</p>

            @auth
                @if(auth()->id() === $comment->user_id)
                    <div class="flex">
                        <button onclick="document.getElementById('edit-{{ $comment->id }}').style.display='block'" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</button>
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Delete comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Delete</button>
                        </form>
                    </div>

                    <form id="edit-{{ $comment->id }}" method="POST" action="{{ route('comments.update', $comment) }}" style="display: none; margin-top: 0.5rem;">
                        @csrf
                        @method('PUT')
                        <textarea name="content" style="width: 100%; padding: 0.5rem;">{{ $comment->content }}</textarea>
                        <button type="submit" class="btn" style="margin-top: 0.5rem;">Update</button>
                    </form>
                @endif

                <button onclick="document.getElementById('reply-{{ $comment->id }}').style.display='block'" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem; margin-top: 0.5rem;">Reply</button>

                <form id="reply-{{ $comment->id }}" method="POST" action="{{ route('comments.store', $post) }}" style="display: none; margin-top: 0.5rem;">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content" placeholder="Write a reply..." style="width: 100%; padding: 0.5rem;" required></textarea>
                    <button type="submit" class="btn" style="margin-top: 0.5rem;">Post Reply</button>
                </form>
            @endauth

            <!-- Replies -->
            @foreach($comment->replies as $reply)
                <div class="comment reply">
                    <div class="comment-meta">
                        <strong>{{ $reply->user->name }}</strong> | {{ $reply->created_at->diffForHumans() }}
                        @if($reply->is_edited)
                            <em>(edited)</em>
                        @endif
                    </div>
                    <p>{{ $reply->content }}</p>

                    @auth
                        @if(auth()->id() === $reply->user_id)
                            <div class="flex">
                                <button onclick="document.getElementById('edit-{{ $reply->id }}').style.display='block'" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</button>
                                <form method="POST" action="{{ route('comments.destroy', $reply) }}" onsubmit="return confirm('Delete reply?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Delete</button>
                                </form>
                            </div>

                            <form id="edit-{{ $reply->id }}" method="POST" action="{{ route('comments.update', $reply) }}" style="display: none; margin-top: 0.5rem;">
                                @csrf
                                @method('PUT')
                                <textarea name="content" style="width: 100%; padding: 0.5rem;">{{ $reply->content }}</textarea>
                                <button type="submit" class="btn" style="margin-top: 0.5rem;">Update</button>
                            </form>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>
        @empty
            <p style="text-align: center; color: #999; padding: 2rem; background: #f8f9fa; border-radius: 5px;">
                No comments yet. Be the first to comment!
            </p>
        @endforelse
    </div>
</div>
@endsection
