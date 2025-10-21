@props(['name', 'label', 'placeholder' => '','type' => 'text', 'required' => true, 'title'])

<div class="py-4 flex-row">

    <label for="{{ $name }}" title="{{ $title }}">
        {{ $label }}
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm">

</div>

<x-forms.form-error :error="$name" />
