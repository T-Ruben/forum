<x-layout>
    <x-header />

    <x-main>

        <div class="bg-gray-500/80 mx-auto border px-10 py-5 rounded-md border-gray-200/25 flex-2 justify-items-center">
            <h1 class="text-2xl pb-3">Create Account</h1>

            <form action="{{ route('register.store') }}" method="POST" class="flex-2 justify-items-end">
                @csrf

                <x-forms.input label="Name" name="name" placeholder="Username" title="Minimum of 3 characters."  />
                <x-forms.input label="Email" name="email" placeholder="Email" title="Email" />
                <x-forms.input label=" Password" type="password" name="password" placeholder="Password" title="Password" />
                <x-forms.input label="Password Confirmation" type="password" id="password_confirmation"
                               name="password_confirmation" placeholder="Password" title="Password" />

                <div class="flex justify-center w-full">
                    <x-forms.form-button>Register</x-forms.form-button>
                </div>

            </form>
        </div>



    </x-main>
    <x-footer />

</x-layout>
