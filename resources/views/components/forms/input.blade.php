@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'type' => 'text',
    'required' => true,
    'title' => null,
    'value' => old($name),
    'id' => null,
    'class' => "outline text-black pl-1 ml-1 text-lg bg-white rounded-sm w-full"
])


<div class="items-center gap-4 sm:grid sm:grid-cols-[8rem_1fr]">

    <label class="text-right text-white font-semibold text-shadow-lg/20" for="{{ $name }}" title="{{ $title }}">
        {{ $label }}
    </label>

    @if ($type === 'password')
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}"
            placeholder="{{ $placeholder }}" class="{{ $class }}">
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}"
            placeholder="{{ $placeholder }}" value="{{ $value }}"
            class="{{ $class }}">
    @endif

</div>

<x-forms.form-error class="text-center" :error="$name" />
