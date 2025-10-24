@props(['active' => true, 'user'])

<a  class="{{ $active ? 'text-lg min-w-0 text-gray-300 hover:underline duration-300 truncate block' :
    'text-sm hover:underline duration-300 min-w-0 block' }}"
{{ $attributes }}>
{{ $slot }}</a>

