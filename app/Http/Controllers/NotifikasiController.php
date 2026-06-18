<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display all notifications.
     */
    public function index()
    {
        $notifications = Notification::with('kerjaSama')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.notifikasi.index', compact('notifications'));
    }

    /**
     * Mark a notification as read and redirect to the partnership detail page.
     */
    public function readAndRedirect($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->kerja_sama_id) {
            return redirect()->route('kerja-sama.show', $notification->kerja_sama_id);
        }

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}
