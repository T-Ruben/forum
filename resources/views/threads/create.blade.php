<x-layout>

    <x-header />

    <x-main>

        <div class=' mx-auto h-full w-full px-2 sm:px-10 py-5 flex-2'>
            <h1 class="text-2xl text-center mb-3">Create Thread</h1>

            <form action="{{ route('threads.store', $forum) }}" method="POST" class="inline text-center" id="postForm">
                @csrf
                <x-forms.div>
                    <input class="outline text-black pl-1 ml-1 text-4xl w-[60%] h-12 bg-white rounded-sm" type="text"
                        id="title" name="title" placeholder="Title" value="{{ old('title') }}" required>
                </x-forms.div>

                    <x-editor />

                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        maxlength="5000"
                        class="w-full p-2 bg-gray-200 text-black resize-none overflow-hidden border border-gray-600
                        outline-none"
                        placeholder="Write your post...">{{ old('content') }}</textarea>

                <x-forms.form-error error="title" />
                <x-forms.form-error error="content" />

                <div class="flex justify-center w-full">
                    <x-forms.form-button>Create Thread</x-forms.form-button>
                </div>

            </form>

        </div>



    </x-main>

    <x-footer />

</x-layout>
