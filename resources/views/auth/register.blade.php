<x-layout>
    <x-header />

    <x-main>

        <div class="bg-gray-500/80 mx-auto border px-10 py-5 rounded-md border-gray-200/25 flex-2 justify-items-center select-none">
            <h1 class="text-2xl pb-3">Create Account</h1>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf

                <x-forms.input label="Name" name="name" placeholder="Username" title="Minimum of 3 characters."  />
                <x-forms.input label="Email" name="email" placeholder="Email" title="Email" />

                <x-forms.radio-group label="Gender" name="gender" :options="['Female', 'Male', 'Unspecified']" />
                <x-forms.input label="Location*" name="location" placeholder="" title="Your location." placeholder="Location"
                    title="Optional" />

                {{-- <x-forms.input label="Date of Birth" name="date_of_birth" type="date" required /> --}}

                <x-forms.date />

                <x-forms.input label="Password" type="password" name="password" placeholder="Password" title="Minimum of 3 characters." />
                <x-forms.input label="Password Confirmation" type="password" id="password_confirmation"
                               name="password_confirmation" placeholder="Password" title="Confirm password" />

                <div class="flex justify-center w-full">
                    <x-forms.form-button>Register</x-forms.form-button>
                </div>

            </form>
        </div>



    </x-main>
    <x-footer />

</x-layout>
