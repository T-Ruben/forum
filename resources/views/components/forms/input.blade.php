@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'type' => 'text',
    'required' => true,
    'title' => null,
    'value' => old($name),
    'id' => null
])

<div class="grid grid-cols-[8rem_1fr] items-center gap-4">

    <label class="text-right" for="{{ $name }}" title="{{ $title }}">
        {{ $label }}
    </label>

    @if ($type === 'password')
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}"
            placeholder="{{ $placeholder }}" class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm w-full">
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}"
            placeholder="{{ $placeholder }}" value="{{ $value }}"
            class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm w-full">
    @endif




</div>

<x-forms.form-error class="text-center" :error="$name" />
