@props(['messages'])

@if ($messages)
    @php
        $errorId = $attributes->get('name', '') ? $attributes->get('name') . '-error' : null;
    @endphp
    <ul {{ $attributes->merge(['class' => 'auth-error', 'id' => $errorId]) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
