<x-layout>
    <x-header />

    <x-main>

        <div class="bg-gray-500/80 mx-auto border px-10 py-5 rounded-md border-gray-200/25 flex-2 justify-items-center">
            <h1 class="text-2xl pb-3">Login</h1>

            <form action="{{ route('login.store') }}" method="POST" class="flex-2 justify-items-end">
                @csrf
                <x-forms.div>
                    <label class="" for="login">Name</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="text" id="login"
                        name="login" placeholder="Username/Email" required>
                </x-forms.div>

                <x-forms.div>
                    <label class="" for="password">Password</label>
                    <input class="outline text-black pl-1 ml-1 text-lg bg-white rounded-sm" type="password"
                        id="password" name="password" placeholder="Password" required>
                </x-forms.div>

                <x-forms.form-error error="password" />
                <x-forms.form-error error="login" />

                <div class="flex justify-center mt-4 w-full h-full select-none">
                    <input class="cursor-pointer" type="checkbox" name="remember" id="remember">
                    <label class="cursor-pointer" for="remember">Remember me</label>
                </div>

                <div class="flex justify-center w-full">
                    <x-forms.form-button>Login</x-forms.form-button>
                </div>

            </form>
        </div>

    </x-main>

</x-layout>
