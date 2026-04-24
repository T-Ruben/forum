@props(['active' => true, 'user', 'page' => 'basic'])



@if ($page === 'settings_link')
    <a class="{{ $active ? 'text-md py-1 pb-1 min-w-0 font-extrabold text-gray-300 hover:bg-gray-400/50 active:bg-gray-400/50
        duration-100 truncate block' :
        'text-md hover:bg-gray-400/50 active:bg-gray-400/50 py-1 pb-1 duration-100 min-w-0 block' }}"
    {{ $attributes }}>
    {{ $slot }}</a>
    @else
    <a class="{{ $active ? 'text-lg min-w-0 text-gray-300 hover:underline active:underline duration-300 truncate block' :
    'text-sm hover:underline active:underline duration-300 min-w-0 block' }}"
    {{ $attributes }}>
    {{ $slot }}</a>
@endif
