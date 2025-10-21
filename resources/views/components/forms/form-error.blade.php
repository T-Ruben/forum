@props(['error'])


<ul class="">
    @error($error)
        <li class="text-red-500 text-md text-shadow-lg/25 will-change-transform translate-z-0">{{ $message }}</li>
    @enderror
</ul>
