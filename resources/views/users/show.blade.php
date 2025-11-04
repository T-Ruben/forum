    <x-forms.profile-image :user="$user" />
<x-layout>
<x-header />



<x-main>

    <div class="flex gap-2">

        <section class="w-[192px]">
            <div class="w-[192px] p-0.5 border">
                <a href="{{ route('settings.personal') }}">
                    <img src="{{ $user->profile_image_url }}"
                    alt="{{ $user->display_name }}"
                    class="cursor-pointer object-cover w-full"
                    itemprop="photo">
                </a>
            </div>
        </section>

        <section class="">
            <div>
                <p class="text-xl">{{ $user->display_name }}</p>
                <p class="text-sm"><span>{{ $user->profile_summary }}</span></p>
            </div>
        </section>

    </div>

</x-main>

<x-footer />
</x-layout>
