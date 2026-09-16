<?php

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $open = false;

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function openNotification(string $id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if (! $notification) {
            return;
        }

        $notification->markAsRead();
        $this->open = false;

        $url = $notification->data['url'] ?? null;

        if ($url) {
            return $this->redirect($url, navigate: true);
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.shared.notification-bell', [
            'notifications' => $user->notifications()->latest()->take(8)->get(),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
