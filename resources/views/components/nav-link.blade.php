@props(["active" => false])

<a aria-current="{{ $active ? 'page' : 'false' }}"
class="{{ $active ? 'h-fit text-gray-500 border-gray-500 border-y-4 font-bold hover:text-gray-500/60 hover:border-gray-500/60' :
 'text-gray-300 h-fit border-gray-300 border-y-2 hover:text-gray-300/60 hover:border-gray-300/60' }}
rounded-md p-2 font-semibold flex-1 text-center duration-300 hover:scale-105 focus:scale-105" {{ $attributes }}>{{ $slot }}</a>
