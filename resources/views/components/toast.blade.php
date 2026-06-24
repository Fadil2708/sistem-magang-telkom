<div id="toast-container"
     class="toast-wrap"
     x-data="{ toasts: [] }"
     @toast.window="
         const id = Date.now() + Math.random();
         toasts.push({ id, message: $event.detail.message, type: $event.detail.type ?? 'success' });
         setTimeout(() => { toasts = toasts.filter(t => t.id !== id); }, 4000);
     "
     x-init="
          @if(session('success'))
              toasts.push({ id: Date.now(), message: @js(session('success')), type: 'success' });
          @endif
          @if(session('error'))
              toasts.push({ id: Date.now() + 1, message: @js(session('error')), type: 'error' });
          @endif
          @if(session('warning'))
              toasts.push({ id: Date.now() + 2, message: @js(session('warning')), type: 'warning' });
          @endif
          @if(session('info'))
              toasts.push({ id: Date.now() + 3, message: @js(session('info')), type: 'info' });
          @endif
     ">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter.duration.300ms.opacity
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:leave.duration.200ms.opacity
             x-transition:leave-end="opacity-0 translate-x-4"
             :class="'toast-card toast-' + (toast.type ?? 'success')"
             class="pointer-events-auto">
            <div class="toast-accent"></div>
            <div class="toast-body">
                <div class="toast-icon-wrap">
                    <i :class="{
                        'ti ti-circle-check': toast.type === 'success',
                        'ti ti-circle-x': toast.type === 'error',
                        'ti ti-alert-circle': toast.type === 'warning',
                        'ti ti-info-circle': toast.type === 'info'
                    }" class="toast-icon"></i>
                </div>
                <p class="toast-msg" x-text="toast.message"></p>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="toast-close" aria-label="Tutup">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>
    </template>
</div>

<script nonce="{{ $cspNonce }}">
    window.showToast = function(message, type = 'success') {
        window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
    };
</script>
