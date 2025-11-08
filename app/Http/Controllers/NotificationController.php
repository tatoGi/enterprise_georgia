<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->post_id) {
            return redirect()->route('posts.show', $notification->post_id);
        }

        return redirect()->route('notifications.index');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->update(['is_read' => true]);

        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read!');
    }
}
