<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index() {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.notification', [
            'notifications' => $notifications,
        ]);
    }

    public function read($id) {
        DB::beginTransaction();

        try {
            $notification = auth()
                ->user()
                ->notifications()
                ->findOrFail($id);

            $notification->markAsRead();

            DB::commit();

            return back()->with('success', 'Notification marked as read!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Failed to mark notification as read. Please try again.');
        }
    }

    public function destroy($id) {
        DB::beginTransaction();

        try {
            $notification = auth()
                ->user()
                ->notifications()
                ->findOrFail($id);

            $notification->delete();

            DB::commit();

            return back()
                ->with('success', 'Notification deleted!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Failed to delete notification. Please try again.');
        }
    }

    public function markAllAsRead() {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            if ($user->unreadNotifications->isEmpty()) {
                return back()
                    ->with('error', 'All notifications are already read.');
            }

            $user->unreadNotifications->markAsRead();

            DB::commit();

            return back()
                ->with('success', 'All notifications marked as read!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Failed to mark all notifications as read. Please try again.');
        }
    }

    public function clearAll() {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            if ($user->notifications()->count() === 0) {
                return back()
                    ->with('error', 'No notifications to clear.');
            }

            $user->notifications()->delete();

            DB::commit();

            return back()
                ->with('success', 'All notifications cleared!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Failed to clear notifications. Please try again.');
        }
    }
}
