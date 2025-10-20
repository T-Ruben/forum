<x-layout>
<x-header />

<x-main>

    <div class="flex gap-3">
        <section class="w-1/6">
            <img src="{{ $user->profile_image_url }}"
                alt="{{ $user->display_name }}"
                class="cursor-pointer object-cover w-full"
                itemprop="photo">
        </section>

        <section class="w-4/6">
            <div>
                <p class="text-xl">{{ $user->display_name }}</p>
                <p>{{ $user->role }}</p>
            </div>
        </section>

    </div>



</x-main>

<x-footer />
</x-layout>
