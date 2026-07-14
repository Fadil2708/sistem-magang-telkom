@props(['name' => 'U', 'size' => 32, 'type' => 'r', 'photo' => null, 'fontSize' => null])

@php
    $initial = strtoupper(substr($name, 0, 1));
    $size = (int) $size;
    $fs = $fontSize ?? ($size * 0.4);
@endphp

@if($photo)
    <img src="{{ Storage::url($photo) }}" loading="lazy" width="{{ $size }}" height="{{ $size }}"
         style="width:{{ $size }}px;height:{{ $size }}px;border-radius:50%;object-fit:cover;border:2px solid #EFF6FF">
@else
    <div class="av {{ $type }}" style="width:{{ $size }}px;height:{{ $size }}px;font-size:{{ $fs }}px">{{ $initial }}</div>
@endif
