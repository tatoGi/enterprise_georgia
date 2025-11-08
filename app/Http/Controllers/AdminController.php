<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,moderator,admin',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('admin.users')->with('success', 'User role updated successfully!');
    }

    public function deletePost(Post $post)
    {
        if ($post->photo) {
            Storage::disk('public')->delete($post->photo);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully!');
    }
}
