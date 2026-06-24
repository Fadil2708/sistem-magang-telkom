@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#5C5A55]']) }}>
    {{ $value ?? $slot }}
    @if($attributes->has('required'))<span class="required">*</span>@endif
</label>
