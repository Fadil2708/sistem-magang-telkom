<?php

namespace App\View\Composers;

use Illuminate\View\View;

class LayoutComposer
{
    public function compose(View $view): void
    {
        $view->with('showProfileStrip', auth()->check() && auth()->user()->isIntern() && !auth()->user()->internProfile?->isComplete());
    }
}
