<x-layout>
    <x-header />

    <x-main>

        <div class="bg-gray-500/80 mx-auto border px-10 py-5 rounded-md border-gray-200/25 flex-2 justify-items-center">
            <h1 class="text-2xl pb-3">Create Account</h1>

            <form action="/register" method="POST" class="flex-2 justify-items-end">
                @csrf
                <x-forms.div>
                    <label class="" for="name">Name</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="text" id="name"
                        name="name" required>
                </x-forms.div>

                <x-forms.form-error name="name" />

                <x-forms.div>
                    <label class="" for="email">Email</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="email"
                        id="email" name="email" required>
                </x-forms.div>

                <x-forms.form-error name="email" />

                <x-forms.div>
                    <label class="" for="password">Password</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="password"
                        id="password" name="password" required>
                </x-forms.div>

                <x-forms.form-error name="password" />

                <x-forms.div>
                    <label class="" for="password_confirmation">Confirm Password</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="password"
                        id="password_confirmation" name="password_confirmation" required>
                </x-forms.div>

                <x-forms.form-error name="password_confirmation" />

                <div class="flex justify-center w-full">
                    <button type="submit"
                        class="mt-5 text-xl cursor-pointer border border-gray-400 rounded-md p-1 dark:bg-blue-950 hover:dark:bg-blue-950/80 duration-200">
                        Register
                    </button>
                </div>

            </form>
        </div>



    </x-main>
    <x-footer />

</x-layout>
