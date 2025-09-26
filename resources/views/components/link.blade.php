@props(['active' => true])

<a  class="{{ $active ? 'my-2 text-lg text-gray-300 hover:underline duration-300 inline-block' :
    'text-sm hover:underline duration-300 block' }}"
{{ $attributes }}>
{{ $slot }}</a>

