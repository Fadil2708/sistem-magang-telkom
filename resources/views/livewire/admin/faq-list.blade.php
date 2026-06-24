<div>
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <i class="ti ti-chevron-right"></i>
                <span>FAQ</span>
            </div>
            <h2 class="page-title">Pertanyaan Umum (FAQ)</h2>
            <p class="page-sub">Kelola pertanyaan umum yang ditampilkan di halaman utama</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <i class="ti ti-plus"></i> Tambah FAQ
        </button>
    </div>

    @if($editingId)
    <div class="panel form-card" style="margin-bottom:16px">
        <h3 class="text-h3" style="margin-bottom:16px">{{ $editingId === 'new' ? 'Tambah' : 'Edit' }} FAQ</h3>
        <form wire:submit="save">
            <div class="form-row">
                <div class="field">
                    <label>Pertanyaan <span class="required">*</span></label>
                    <input wire:model="question" type="text" class="input" placeholder="Masukkan pertanyaan">
                    @error('question') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Urutan</label>
                    <input wire:model="sort_order" type="number" class="input" min="0">
                    @error('sort_order') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="field">
                <label>Jawaban <span class="required">*</span></label>
                <textarea wire:model="answer" rows="4" class="input" placeholder="Masukkan jawaban"></textarea>
                @error('answer') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="btn-bar" style="margin-top:16px">
                <button type="button" wire:click="cancel" class="btn-outline">
                    <i class="ti ti-x"></i> Batal
                </button>
                <button type="submit"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                        class="btn-save">
                    <i wire:loading.remove class="ti ti-device-floppy"></i>
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading class="inline-flex items-center gap-1">
                        <i class="ti ti-loader animate-spin"></i> Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="panel" style="overflow-x:auto">
        <table class="data">
            <thead>
                <tr>
                    <th style="width:40px">No</th>
                    <th>Pertanyaan</th>
                    <th style="width:80px">Urutan</th>
                    <th style="width:80px">Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span style="font-weight:600;color:#1E1C1A;font-size:13px">{{ $faq->question }}</span>
                    </td>
                    <td>{{ $faq->sort_order }}</td>
                    <td>
                        <span class="badge {{ $faq->is_active ? 'accepted' : 'rejected' }}">
                            {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button wire:click="edit('{{ $faq->id }}')" class="action-btn" title="Edit">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button wire:click="toggleActive('{{ $faq->id }}')"
                                    wire:loading.attr="disabled"
                                    class="action-btn {{ $faq->is_active ? 'danger' : 'success' }}"
                                    title="{{ $faq->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="ti ti-{{ $faq->is_active ? 'eye-off' : 'eye' }}"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <x-empty-state icon="ti-message-off" message="Belum ada FAQ." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $faqs->links() }}
    </div>
</div>
