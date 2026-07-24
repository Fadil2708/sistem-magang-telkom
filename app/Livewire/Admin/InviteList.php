<?php

namespace App\Livewire\Admin;

use App\Models\RegistrationInvite;
use App\Services\InviteService;
use Livewire\Component;
use Livewire\WithPagination;

class InviteList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    private InviteService $inviteService;

    public function boot(InviteService $inviteService): void
    {
        $this->inviteService = $inviteService;
    }

    public function generate(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $invite = $this->inviteService->generate('supervisor', null);
        session()->flash('inviteCode', $invite->code);
        $this->dispatch('toast', message: 'Kode undangan berhasil dibuat.', type: 'success');
    }

    public function render()
    {
        $invites = $this->inviteService->getPaginatedList();
        return view('livewire.admin.invite-list', compact('invites'));
    }
}