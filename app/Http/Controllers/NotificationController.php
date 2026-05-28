<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnread()
    {
        $notifications = Notifikasi::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $count = $notifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }
    
    public function markAsRead($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    public function markAllRead()
    {
        Notifikasi::where('is_read', false)->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    public function index()
    {
        $notifications = Notifikasi::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }
}