<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog Platform')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        header { background: #2c3e50; color: white; padding: 1rem 0; margin-bottom: 2rem; }
        header .container { display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.5rem; }
        nav a { color: white; text-decoration: none; margin-left: 20px; }
        nav a:hover { text-decoration: underline; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 0.5rem 1rem; background: #3498db; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        .btn-secondary { background: #95a5a6; }
        .btn-secondary:hover { background: #7f8c8d; }
        .card { background: white; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 3px; }
        .form-group textarea { min-height: 150px; }
        .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.875rem; }
        .badge-pending { background: #f39c12; color: white; }
        .badge-approved { background: #27ae60; color: white; }
        .badge-rejected { background: #e74c3c; color: white; }
        .badge-user { background: #3498db; color: white; }
        .badge-moderator { background: #9b59b6; color: white; }
        .badge-admin { background: #e67e22; color: white; }
        .notification-badge { background: #e74c3c; color: white; padding: 2px 6px; border-radius: 50%; font-size: 0.75rem; margin-left: 5px; }
        .comment { padding: 1rem; background: #f8f9fa; margin-bottom: 1rem; border-left: 3px solid #3498db; border-radius: 3px; }
        .comment.reply { margin-left: 40px; border-left-color: #95a5a6; }
        .comment-meta { font-size: 0.875rem; color: #666; margin-bottom: 0.5rem; }
        .post-image { max-width: 100%; height: auto; margin: 1rem 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        table th, table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #ecf0f1; font-weight: bold; }
        .pagination { display: flex; gap: 0.5rem; margin: 1rem 0; }
        .pagination a, .pagination span { padding: 0.5rem 0.75rem; border: 1px solid #ddd; text-decoration: none; color: #333; }
        .pagination .active { background: #3498db; color: white; border-color: #3498db; }
        .text-center { text-align: center; }
        .mb-2 { margin-bottom: 1rem; }
        .mt-2 { margin-top: 1rem; }
        .flex { display: flex; gap: 0.5rem; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="{{ route('home') }}" style="color: white; text-decoration: none;">Blog Platform</a></h1>
            <nav>
                <a href="{{ route('home') }}">Home</a>
                @auth
                    <a href="{{ route('posts.create') }}">Create Post</a>
                    <a href="{{ route('posts.my') }}">My Posts</a>
                    <a href="{{ route('notifications.index') }}">
                        Notifications
                        @if(auth()->user()->notifications()->unread()->count() > 0)
                            <span class="notification-badge">{{ auth()->user()->notifications()->unread()->count() }}</span>
                        @endif
                    </a>
                    @if(auth()->user()->isModerator() || auth()->user()->isAdmin())
                        <a href="{{ route('moderation.index') }}">Moderation Queue</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users') }}">Admin Panel</a>
                    @endif
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
