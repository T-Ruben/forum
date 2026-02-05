@props(['user'])

<div id="avatarEditor" class="hidden justify-center items-center fixed z-10 w-full h-full bg-black/50">

    <div id="editorContent" class="border-black h-fit border w-full max-w-4xl p-4 gap-3 bg-black/75">
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

        <div class="w-fit">
            <form action="{{ route('avatar.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <fieldset class="p-2 rounded-sm border mb-2">
                    <label class="cursor-pointer" for="avatar">Upload a new custom avatar: </label>
                    <input class="w-full border border-gray-500 hover:bg-gray-400/30 transition-colors duration-200
                        bg-gray-400/60 cursor-pointer p-2 text-white rounded file:mr-4 file:py-2 file:px-4 file:text-sm file:font-semibold file:bg-gray-600 file:text-white hover:file:bg-gray-500
                        file:cursor-pointer"
                    type="file" name="avatar" id="avatar" accept="image/*">
                    <p class="text-xs pt-1 text-gray-500">Image size should not exceed 2MB.</p>
                </fieldset>
                <div class="flex gap-2 justify-between">
                    <div class="w-fit">
                        <button type="button" class="border border-white bg-red-600/75 py-1 px-2 cursor-pointer
                            hover:bg-red-600/50 duration-200"
                            onclick="document.querySelector('#avatar-delete').submit()">Delete Avatar</button>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" id="submit" class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                            py-1 px-2 cursor-pointer">Upload</button>
                        <button type="button" id="closeBtn" class="border hover:bg-gray-400/30 duration-200 bg-gray-400/60
                            py-1 px-2 cursor-pointer">Close</button>
                    </div>

                </div>
                @error('avatar')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('avatar-delete')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror

            </form>
        </div>
        </div>

        </div>
    </div>
</div>

<form action="{{ route('avatar.destroy') }}" method="POST" id="avatar-delete" class="hidden">
    @csrf
    @method('DELETE')
</form>
