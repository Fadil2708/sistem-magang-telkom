@props(['disabled' => false])

@php
    $name = $attributes->get('name');
    $hasError = $name && $errors->has($name);
@endphp

<input @disabled($disabled)
       {{ $attributes->merge([
            'class' => 'border-[#E0DDD8] focus:border-[#C0392B] focus:ring-[#C0392B] rounded-xl bg-white' . ($hasError ? ' border-red-500 bg-red-50' : ''),
           'aria-invalid' => $hasError ? 'true' : 'false',
           'aria-describedby' => $hasError ? $name . '-error' : null,
       ]) }}>
