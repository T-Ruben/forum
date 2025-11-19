<x-layout>
<x-header />



<x-main>

    @push('modals')
    <x-forms.profile-image :user="$user" />
    @endpush

    <div class="flex gap-2">

        <section class="w-[192px]">
            <div id="avatarChange" class="w-[192px] p-0.5 border">
                    <img src="{{ $user->profile_image_url }}"
                    alt="{{ $user->display_name }}"
                    class="cursor-pointer object-cover w-full"
                    data-pin-nopin="true"
                    itemprop="photo">
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
