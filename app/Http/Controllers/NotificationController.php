<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return the authenticated user's unread notifications as JSON.
     */
    public function unread()
    {
        return Auth::user()->notifications()
            ->unread()
            ->limit(10)
            ->get(['id', 'type', 'title', 'message', 'link', 'created_at']);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return $notification->link ? redirect()->to($notification->link) : redirect()->back();
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->back();
    }
}
