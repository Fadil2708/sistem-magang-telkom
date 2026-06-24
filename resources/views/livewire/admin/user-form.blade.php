<div>
    <div class="page-header" style="margin-bottom:16px">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('admin.users') }}">Pengguna</a>
                <i class="ti ti-chevron-right"></i>
                <span>{{ $isEditing ? 'Edit' : 'Buat Baru' }}</span>
            </div>
            <h2 class="page-title">{{ $isEditing ? 'Edit Pengguna' : 'Buat Pengguna Baru' }}</h2>
        </div>
    </div>
    <form wire:submit="save">
        <div class="form-layout">
            <div class="form-main">
                <div class="form-row-3">
                    <div class="field">
                        <label>Email</label>
                        <input wire:model="email" type="email" class="input">
                        @error('email') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Role</label>
                        <select wire:model="role" class="input">
                            <option value="intern">Peserta</option>
                            <option value="supervisor">Pembimbing</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="field">
                        <label>Password</label>
                        <input wire:model="password" type="password" class="input">
                        @error('password') <div class="field-error">{{ $message }}</div> @enderror
                        @if($isEditing)
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                        @endif
                    </div>
                    <div class="field">
                        <label>Konfirmasi Password</label>
                        <input wire:model="password_confirmation" type="password" class="input">
                    </div>
                </div>
                <div class="field" style="padding-top:8px">
                    <label class="inline-flex items-center gap-3">
                        <input wire:model="is_active" type="checkbox" class="input" style="width:auto">
                        <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4" style="margin-top:24px">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                <i wire:loading.remove class="ti ti-device-floppy" style="font-size:16px"></i>
                <span wire:loading.remove>{{ $isEditing ? 'Perbarui' : 'Buat' }} Pengguna</span>
                <span wire:loading class="inline-flex items-center gap-1">
                    <i class="ti ti-loader animate-spin" style="font-size:16px"></i>
                    Menyimpan...
                </span>
            </button>
            <a href="{{ route('admin.users') }}" wire:navigate class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
