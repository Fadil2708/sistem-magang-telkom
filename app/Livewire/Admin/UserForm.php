<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\UserService;
use Livewire\Component;

class UserForm extends Component
{
    public ?User $user = null;
    public $email = '';
    public $role = 'intern';
    public $password = '';
    public $password_confirmation = '';
    public $is_active = true;

    public bool $isEditing = false;

    private UserService $userService;

    public function boot(UserService $userService): void
    {
        $this->userService = $userService;
    }

    protected $rules = [
        'email' => 'required|email|max:255|unique:users,email',
        'role' => 'required|in:admin,supervisor,intern',
        'password' => 'required|min:8|confirmed',
        'is_active' => 'boolean',
    ];

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->user = User::findOrFail($id);
            $this->email = $this->user->email;
            $this->role = $this->user->role;
            $this->is_active = $this->user->is_active;
            $this->isEditing = true;
        }
    }

    public function updated($propertyName): void
    {
        if ($this->isEditing) {
            $this->validateOnly($propertyName, [
                'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
                'role' => 'required|in:admin,supervisor,intern',
                'password' => 'nullable|min:8|confirmed',
                'is_active' => 'boolean',
            ]);
        } else {
            $this->validateOnly($propertyName);
        }
    }

    public function save(): void
    {
        if ($this->isEditing) {
            $this->validate([
                'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
                'role' => 'required|in:admin,supervisor,intern',
                'password' => 'nullable|min:8|confirmed',
                'is_active' => 'boolean',
            ]);
            $this->userService->update($this->user, [
                'email' => $this->email,
                'role' => $this->role,
                'is_active' => $this->is_active,
                'password' => $this->password,
            ]);
            $this->dispatch('toast', message: 'Pengguna berhasil diperbarui.', type: 'success');
        } else {
            $this->validate();
            $this->userService->create([
                'email' => $this->email,
                'role' => $this->role,
                'password' => $this->password,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', message: 'Pengguna berhasil dibuat.', type: 'success');
            $this->resetForm();
        }
    }

    public function resetForm(): void
    {
        $this->reset(['email', 'role', 'password', 'password_confirmation']);
        $this->role = 'intern';
        $this->is_active = true;
        $this->isEditing = false;
        $this->user = null;
    }

    public function render()
    {
        return view('livewire.admin.user-form');
    }
}