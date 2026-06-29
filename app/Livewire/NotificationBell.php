<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public array $recentNotifications = [];

    protected function getListeners(): array
    {
        return ['notification-refresh' => 'refresh'];
    }

    public function refresh(): void
    {
        $user = auth()->user();
        if (!$user) return;

        $this->unreadCount = $user->unreadNotifications()->count();
        $this->recentNotifications = $user->unreadNotifications()
            ->take(5)
            ->get()
            ->toArray();
    }

    public function markAsRead(string $id): void
    {
        auth()->user()->unreadNotifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);
        $this->refresh();
        $this->dispatch('notification-read');
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->refresh();
        $this->dispatch('notification-read');
    }

    public function render()
    {
        $this->refresh();
        return view('livewire.notification-bell');
    }
}
