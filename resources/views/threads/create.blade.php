<x-layout>

    <x-header />

    <x-main>

        <div class=' mx-auto h-full w-full  px-10 py-5  flex-2'>
            <h1 class="text-2xl text-center mb-5">Create Thread</h1>
            <p></p>


            <form action="{{ route('threads.store', $forum) }}" method="POST" class="inline text-center">
                @csrf
                <x-forms.div>
                    <input class="outline text-black pl-1 ml-1 text-4xl w-[60%] h-12 bg-white rounded-sm" type="text"
                        id="title" name="title" placeholder="Title" required>
                </x-forms.div>

                <x-forms.div>
                    <textarea class="outline field-sizing-content text-black pl-1 ml-1 w-[60%] min-h-96 bg-white resize-none"
                        name="content" id="content" required></textarea>
                </x-forms.div>

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
