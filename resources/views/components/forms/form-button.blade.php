@props(['textSize' => 'text-xl'])

<button type="submit"
       {{ $attributes->merge(['class' => "mt-5 {$textSize} cursor-pointer border border-gray-400
        rounded-md p-2 dark:bg-blue-950 hover:dark:bg-blue-900/80 active:dark:bg-blue-900/80 duration-200"]) }}>
    {{ $slot }}
</button>
