@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="flex-between mb-2">
    <h2>Notifications</h2>
    @if($notifications->where('is_read', false)->count() > 0)
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">Mark All as Read</button>
        </form>
    @endif
</div>

@forelse($notifications as $notification)
    <div class="card" style="background: {{ $notification->is_read ? '#fff' : '#e8f4f8' }};">
        <div class="flex-between">
            <div>
                <p style="margin-bottom: 0.5rem;">{{ $notification->message }}</p>
                <small style="color: #666;">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            <div class="flex">
                @if(!$notification->is_read)
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Mark as Read</button>
                    </form>
                @endif
                @if($notification->post_id)
                    <a href="{{ route('posts.show', $notification->post_id) }}" class="btn">View Post</a>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="card text-center">
        <p>No notifications.</p>
    </div>
@endforelse

<div class="pagination">
    {{ $notifications->links() }}
</div>
@endsection
