@props(['time'])


@if ($time)
    <span class="post-time" data-time="{{ $time->toIso8601String() }}">

    </span>
@else
    <span class="text-gray-400 italic"></span>
@endif

