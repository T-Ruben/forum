<x-layout>

    <x-header />

    <x-main>

        <div class=' mx-auto h-full w-full  px-10 py-5  flex-2'>
            <h1 class="text-2xl text-center mb-5">Create Thread</h1>
            <p></p>


            <form action="{{ route('threads.store', $forum) }}" method="POST" class="inline text-center" id="postForm">
                @csrf
                <x-forms.div>
                    <input class="outline text-black pl-1 ml-1 text-4xl w-[60%] h-12 bg-white rounded-sm" type="text"
                        id="title" name="title" placeholder="Title" value="{{ old('title') }}" required>
                </x-forms.div>

                    <div id="editor" class="border text-black bg-gray-100 mb-1">
                        <div id="toolbar-container">
                            <span class="ql-formats">
                                <select class="ql-font"></select>
                                <select class="ql-size"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                                <button class="ql-strike"></button>
                            </span>
                            <span class="ql-formats">
                                <select class="ql-color"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-list" value="ordered"></button>
                                <button class="ql-list" value="bullet"></button>
                                <button class="ql-indent" value="-1"></button>
                                <button class="ql-indent" value="+1"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-link"></button>
                                <button class="ql-image"></button>
                                <button class="ql-video"></button>
                            </span>

                            <span class="ql-formats">
                                <button class="ql-clean"></button>
                            </span>
                        </div>
                        <div id="editor-container" class="min-h-42 w-full p-2 bg-white text-black border rounded"></div>
                    </div>

                    <input type="hidden" id="content" name="content">

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
