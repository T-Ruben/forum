@props(['user'])

<div class="flex justify-center fixed z-10 w-full top-[25%]">

    <div class="border-black border p-4 gap-3 bg-black/75">

        <p class="bg-gray-600 p-2 col-span-12">Avatar Editor</p>

        <div class="flex justify-between gap-2 mt-2">
        <div class="block border w-full p-0.5 border-white/75">
            <img src="{{ Auth::user()->profile_image_url }}"
            alt="{{ Auth::user()->display_name }}"
            class="cursor-pointer object-cover w-full h-full"
            itemprop="photo"
            id="imagePreview">
        </div>

        <div class="w-full">
            <form action="{{ route('user.avatar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <fieldset class="p-2 rounded-sm border mb-2">
                    <label class="cursor-pointer" for="avatar">Upload a new custom avatar: </label>
                    <input class="border w-full hover:bg-gray-400/30 duration-200 bg-gray-400/60 cursor-pointer"
                    type="file" name="avatar" id="avatar" accept="image/*">
                </fieldset>
                <div class="flex gap-2 justify-end">
                    <button type="submit" class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                        py-1 px-2 cursor-pointer">Upload</button>
                    <button class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                        py-1 px-2 cursor-pointer">Close</button>
                </div>
                <x-forms.form-error error="avatar" />
            </form>
        </div>
        </div>


    </div>
</div>
