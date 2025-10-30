<x-layout>
    <x-header />

    <x-main>

        <div class="bg-gray-500/80 mx-auto border px-10 py-5 rounded-md border-gray-200/25 flex-2 justify-items-center">
            <h1 class="text-2xl pb-3">Login</h1>

            <form action="{{ route('login.store') }}" method="POST" class="flex-2 justify-items-end">
                @csrf

                <div class="space-y-4">
                    <x-forms.input name="login" label="Name" placeholder="Username/Email" title="Title" />
                    <x-forms.input type="password" name="password" label="Password" placeholder="Password" title="Password" />
                </div>


                <div class="flex justify-center mt-4 w-full h-full select-none">
                    <input class="cursor-pointer" type="checkbox" name="remember" id="remember">
                    <label class="cursor-pointer" for="remember">Remember me</label>
                </div>

                @if (session('success'))
                    <p class="text-gray-300 p-2 border mt-2 bg-linear-to-bl from-teal-500 to-cyan-500 shadow-black shadow-md">
                        {{ session('success') }}</p>
                @endif

                <div class="flex justify-center w-full">
                    <x-forms.form-button>Login</x-forms.form-button>
                </div>

            </form>
        </div>

    </x-main>

</x-layout>
