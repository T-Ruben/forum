@props(['user'])

<div class="flex justify-center items-center fixed z-10 w-full h-full bg-black/50">

    <div class="border-black h-fit border w-full max-w-4xl p-4 gap-3 bg-black/75">
        <div class="bg-black/50 p-1">
        <p class="bg-gray-600 p-2 col-span-12">Avatar Editor</p>

        <div class="flex justify-between gap-2 mt-2">
        <div class="block border w-fit p-0.5 border-white/75 max-md:hidden">
            <img src="{{ $user->profile_image_url }}"
            alt="{{ $user->display_name }}"
            class="cursor-pointer object-cover w-[192px] h-full"
            itemprop="photo"
            id="imagePreview">
        </div>

        <div class="w-auto flex-2 ">
            <div id="croppieContainer" class="w-32 h-32 border overflow-hidden" data-pin-nopin="true"></div>

            <div>
                <button id="cropBtn" type="submit" class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                        py-1 px-2 cursor-pointer">Save</button>
            </div>

            <input type="file" id="avatarInput" accept="image/*" class="border w-full hover:bg-gray-400/30 duration-200 bg-gray-400/60 cursor-pointer">

            <form action="{{ route('user.avatar.update') }}" method="POST" id="avatarForm" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" id="croppedImageInput" name="croppedImage">
            </form>

        </div>

        <div></div>

        {{-- <div class="w-fit">
            <form action="{{ route('user.avatar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <fieldset class="p-2 rounded-sm border mb-2">
                    <input type="hidden" name="cropped_image" id="croppedImage">
                    <label class="cursor-pointer" for="avatar">Upload a new custom avatar: </label>
                    <input class="border w-full hover:bg-gray-400/30 duration-200 bg-gray-400/60 cursor-pointer"
                    type="file" name="avatar" id="avatar" accept="image/*">
                    <p class="text-xs pt-1 text-gray-500">Image size should not exceed 2MB.</p>
                </fieldset>
                <div class="flex gap-2 justify-end">
                    <button id="submit" class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                        py-1 px-2 cursor-pointer">Upload</button>
                    <span class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                        py-1 px-2 cursor-pointer">Close</span>
                </div>
                <x-forms.form-error error="avatar" />
            </form>
        </div> --}}
        </div>

        </div>
    </div>
</div>
