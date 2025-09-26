@props(['name'])


<div class="flex w-full justify-center">
    @error($name)
        <p class="text-red-500 text-md text-shadow-lg/25 will-change-transform translate-z-0">{{ $message }}</p>
    @enderror
</div>
