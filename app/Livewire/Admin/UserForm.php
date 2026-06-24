<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm extends Component
{
    public ?User $user = null;
    public $email = '';
    public $role = 'intern';
    public $password = '';
    public $password_confirmation = '';
    public $is_active = true;

    public bool $isEditing = false;

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

    public function rules(): array
    {
        $uniqueRule = $this->isEditing
            ? 'unique:users,email,' . $this->user->id
            : 'unique:users,email';

        $passwordRule = $this->isEditing ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed';

        return [
            'email' => ['required', 'email', $uniqueRule],
            'password' => $passwordRule,
            'password_confirmation' => $this->isEditing ? 'nullable' : 'required',
            'role' => 'required|in:admin,supervisor,intern',
            'is_active' => 'boolean',
        ];
    }

    public function save(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $this->validate();

        if ($this->isEditing) {
            $data = [
                'email' => $this->email,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $this->user->update($data);
            session()->flash('success', 'Pengguna berhasil diperbarui.');
        } else {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'is_active' => $this->is_active,
            ]);

            if ($this->role === 'intern') {
                $user->internProfile()->create(['id' => (string) Str::uuid()]);
            } elseif ($this->role === 'supervisor') {
                $user->supervisorProfile()->create(['id' => (string) Str::uuid()]);
            }

            session()->flash('success', 'Pengguna berhasil dibuat.');
        }

        $this->redirect(route('admin.users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.user-form');
    }
}
