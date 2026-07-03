@php
    $flashToasts = [];
    if (session('success')) $flashToasts[] = ['message' => session('success'), 'type' => 'success'];
    if (session('error')) $flashToasts[] = ['message' => session('error'), 'type' => 'error'];
    if (session('warning')) $flashToasts[] = ['message' => session('warning'), 'type' => 'warning'];
    if (session('info')) $flashToasts[] = ['message' => session('info'), 'type' => 'info'];
@endphp
<div id="toast-container"
     class="toast-wrap"
     x-data="toastStack(@json($flashToasts))"
     @toast.window="add">
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
                <button @click="remove(toast.id)" class="toast-close" aria-label="Tutup">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>
    </template>
</div>


