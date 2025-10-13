@props(['user'])

@if ($user)
    <a class="hover:underline truncate" href="{{ route('users.show', $user) }}">
    {{ $user->display_name ?? 'Deleted Member' }},
    </a>
@endif
