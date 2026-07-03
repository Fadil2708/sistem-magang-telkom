<?php

namespace App\View\Composers;

use Illuminate\View\View;

class LayoutComposer
{
    public function compose(View $view): void
    {
        $path = str_replace('\\', '/', $view->getPath());
        if (!str_ends_with($path, 'layouts/app.blade.php')) {
            return;
        }

        $view->with('showProfileStrip', auth()->check() && auth()->user()->isIntern() && !auth()->user()->internProfile?->isComplete());
    }
}
