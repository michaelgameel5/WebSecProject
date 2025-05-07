<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        
        // Mark all unread notifications as read
        auth()->user()->unreadNotifications->markAsRead();
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notifications marked as read']);
    }
} 