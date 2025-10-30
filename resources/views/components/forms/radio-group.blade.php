@props(['label', 'name', 'options'])

<div class="grid grid-cols-[8rem_1fr] items-center gap-4">
    <label class="text-right">{{ $label }}</label>
    <div class="flex space-x-6">
        @foreach ($options as $option)
            <label class="inline-flex items-center space-x-2 select-none">
                <input type="radio" name="{{ $name }}" value="{{ $option }}">
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
</div>
<x-forms.form-error class="text-center" :error="$name" />
