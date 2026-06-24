<?php

namespace App\Livewire\Admin;

use App\Models\RegistrationInvite;
use Livewire\Component;
use Livewire\WithPagination;

class InviteList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function generate(): void
    {
        $invite = RegistrationInvite::generate('supervisor', expiresAt: now()->addHour());
        session()->flash('inviteCode', $invite->code);
        $this->dispatch('toast', message: 'Kode undangan berhasil dibuat.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.invite-list', [
            'invites' => RegistrationInvite::with('creator')
                ->latest()
                ->paginate(20),
        ]);
    }
}
