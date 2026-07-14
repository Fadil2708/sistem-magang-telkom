<div x-data="confirmModal"
@confirm.window="show($event.detail.message, $event.detail.callback)"
x-show="open"
x-cloak
class="modal-wrap"
role="dialog"
aria-modal="true">
<div class="modal-backdrop" @click="open = false"></div>
<div class="modal-center">
    <div class="modal-card" style="max-width:400px;text-align:center">
        <div class="modal-header">
            <h3 class="modal-title">Konfirmasi</h3>
            <button @click="open = false" class="action-btn" aria-label="Tutup">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="modal-body" style="padding:32px 24px">
            <div style="font-size:48px;color:#2563EB;margin-bottom:16px">
                <i class="ti ti-alert-triangle"></i>
            </div>
            <p style="font-size:15px;line-height:1.6;color:#333" x-text="message"></p>
        </div>
        <div class="modal-footer" style="justify-content:center;gap:12px">
            <button @click="open = false" class="btn-secondary">Batal</button>
            <button @click="confirm" class="btn-save" style="background:#2563EB;min-width:140px">
                Ya, lanjutkan
            </button>
        </div>
    </div>
</div>
</div>
