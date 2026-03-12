@props(['time', 'updatedAt' => null, 'createdAt' => null])


@if ($time)
    <span
        {{ $attributes->merge(['class' => "post-time"]) }}
        data-time="{{ $time->toIso8601String() }}"
        x-data
        x-init="$el.textContent = getRelativeTime(new Date($el.dataset.time))">
        {{ $time->diffForHumans() }}
    </span>
    @if($updatedAt && $createdAt && $updatedAt->gt($createdAt))
        <small class="text-gray-200 italic">(edited)</small>
    @endif
@else
    <span class="text-gray-200 italic"></span>
@endif

