<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function readAll(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }

    public function read(string $id, Request $request): RedirectResponse
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        $redirect = $request->input('redirect', route('notifications'));

        return redirect($redirect);
    }
}
