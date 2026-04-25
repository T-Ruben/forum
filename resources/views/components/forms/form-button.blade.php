@props(['textSize' => 'text-xl'])

<button type="submit"
       {{ $attributes->merge(['class' => "mt-5 {$textSize} cursor-pointer border border-gray-400
        rounded-md p-2 bg-blue-950 hover:bg-blue-900/80 active:bg-blue-900/80 duration-200"]) }}>
    {{ $slot }}
</button>
