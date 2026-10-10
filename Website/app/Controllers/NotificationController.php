<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Flash;
use App\Models\Notification;

class NotificationController
{
    public function index(): void
    {
        $userId = Auth::id();
        $notifications = Notification::getForUser($userId, 30);
        $unreadCount = Notification::getUnreadCount($userId);

        view('student/notifications', [
            'title' => 'Pusat Notifikasi — TemanBelajar',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead(int $id): void
    {
        $userId = Auth::id();
        Notification::markAsRead($id, $userId);

        $redirectUrl = $_GET['redirect'] ?? '/notifications';
        redirect($redirectUrl);
    }

    public function markAllAsRead(): void
    {
        $userId = Auth::id();
        Notification::markAllAsRead($userId);

        Flash::success('Semua notifikasi telah ditandai sebagai dibaca.');
        redirect('/notifications');
    }
}
