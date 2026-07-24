<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public $notifications = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        $this->unreadCount = $user->unreadNotifications->count();
        $this->notifications = $user->notifications()->latest()->take(10)->get();
    }

    public function markAsRead(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        $this->loadNotifications();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}