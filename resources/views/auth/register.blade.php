<x-layout>
    <x-header />

    <x-main>

        <div class="bg-gray-500/80 mx-auto border px-10 py-5 rounded-md border-gray-200/25 flex-2 justify-items-center">
            <h1 class="text-2xl pb-3">Create Account</h1>

            <form action="{{ route('register.store') }}" method="POST" class="flex-2 justify-items-end">
                @csrf
                <x-forms.div>
                    <label class="" for="name">Name</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="text" id="name"
                        name="name" placeholder="Username" required>
                </x-forms.div>

                <x-forms.form-error error="name" />

                <x-forms.div>
                    <label class="" for="email">Email</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="email"
                        id="email" name="email" placeholder="game@example.com" required>
                </x-forms.div>

                <x-forms.form-error error="email" />

                <x-forms.div>
                    <label class="" for="password">Password</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="password"
                        id="password" name="password" placeholder="Password" required>
                </x-forms.div>

                <x-forms.form-error error="password" />

                <x-forms.div>
                    <label class="" for="password_confirmation">Confirm Password</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="password"
                        id="password_confirmation" name="password_confirmation" placeholder="Password" required>
                </x-forms.div>

                <x-forms.form-error error="password_confirmation" />

                <div class="flex justify-center w-full">
                    <x-forms.form-button>Register</x-forms.form-button>
                </div>

            </form>
        </div>



    </x-main>
    <x-footer />

</x-layout>
