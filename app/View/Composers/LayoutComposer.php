<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LayoutComposer
{
    public function compose(View $view): void
    {
        $path = str_replace('\\', '/', $view->getPath());
        if (!str_ends_with($path, 'layouts/app.blade.php')) {
            return;
        }

        $showStrip = false;
        $user = auth()->user();

        if ($user && $user->isIntern()) {
            $showStrip = !Cache::remember(
                "profile_complete_{$user->id}",
                300,
                fn () => $user->internProfile?->isComplete() ?? false
            );
        }

        $view->with('showProfileStrip', $showStrip);
    }
}
