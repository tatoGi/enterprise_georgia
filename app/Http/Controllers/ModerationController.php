<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->pending()->latest()->paginate(10);
        return view('moderation.index', compact('posts'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);

        // Notify post author
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => 'Your post "' . $post->title . '" has been approved!',
            'post_id' => $post->id,
        ]);

        return redirect()->route('moderation.index')->with('success', 'Post approved successfully!');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);

        // Notify post author
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => 'Your post "' . $post->title . '" has been rejected.',
            'post_id' => $post->id,
        ]);

        return redirect()->route('moderation.index')->with('success', 'Post rejected.');
    }
}
