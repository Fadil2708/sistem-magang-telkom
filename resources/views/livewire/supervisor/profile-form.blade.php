<div>
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('supervisor.dashboard') }}">Dashboard</a>
                <i class="ti ti-chevron-right"></i>
                <span>Profil</span>
            </div>
            <h2 class="page-title">Profil Pembimbing</h2>
        </div>
    </div>
    <form wire:submit="save">
        <div class="form-layout">
            <div class="form-main">
                <div class="form-row-3">
                    <div class="field">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input wire:model="full_name" type="text" class="input">
                        @error('full_name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>NIP</label>
                        <input wire:model="employee_id" type="text" class="input">
                        @error('employee_id') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="field">
                        <label>Divisi</label>
                        <input wire:model="division" type="text" class="input">
                        @error('division') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Jabatan</label>
                        <input wire:model="position" type="text" class="input">
                        @error('position') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="field">
                    <label>No. Telepon</label>
                    <input wire:model="phone" type="text" class="input">
                    @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                <i wire:loading.remove class="ti ti-device-floppy"></i>
                <span wire:loading.remove>Simpan Profil</span>
                <span wire:loading class="inline-flex items-center gap-1">
                    <i class="ti ti-loader animate-spin"></i>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
