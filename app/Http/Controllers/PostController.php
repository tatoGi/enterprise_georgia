<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'comments'])
            ->withCount('comments')
            ->approved()
            ->orderBy('created_at', 'desc');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $posts = $query->paginate(10);
        $categories = Post::distinct()->pluck('category');

        return view('posts.index', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        // Guests and all users can see only approved posts
        if ($post->status !== 'approved' && (!auth()->check() || auth()->id() !== $post->user_id)) {
            abort(403, 'This post is not available.');
        }

        $post->load('user', 'comments.user', 'comments.replies.user');

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'photo' => $photoPath,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Notify all moderators about new post
        $moderators = User::where('role', 'moderator')->get();
        foreach ($moderators as $moderator) {
            Notification::create([
                'user_id' => $moderator->id,
                'type' => 'new_post',
                'message' => 'New post "' . $post->title . '" submitted by ' . auth()->user()->name,
                'post_id' => $post->id,
            ]);
        }

        return redirect()->route('posts.my')->with('success', 'Post created and submitted for approval!');
    }

    public function edit(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $post->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($post->photo) {
                Storage::disk('public')->delete($post->photo);
            }
            $photoPath = $request->file('photo')->store('posts', 'public');
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'photo' => $photoPath,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($post->photo) {
            Storage::disk('public')->delete($post->photo);
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully!');
    }

    public function myPosts()
    {
        $posts = Post::where('user_id', auth()->id())->latest()->paginate(10);
        return view('posts.my', compact('posts'));
    }
}
