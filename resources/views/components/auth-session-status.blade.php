@props(['status'])

@if ($status)
    <div class="alert-success">
        <i class="ti ti-circle-check"></i>
        {{ $status }}
    </div>
@endif
