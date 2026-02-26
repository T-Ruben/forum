<x-settings.aside>
    <x-slot:title>Personal Details</x-slot:title>

    <form action="{{ route('users.destroy', [$user, $user->id]) }}" method="POST">
        @csrf
        @method('DELETE')

        @can('delete', $user)
            <x-forms.form-button onclick="return confirm('Are you sure? This action cannout be reversed.')"
                title="Delete account"
                alt="Delete account. This action cannout be reversed.">
                Delete Account
            </x-forms.form-button>
        @endcan

    </form>
    <span class="text-sm">This action cannout be reversed.</span>

</x-settings.aside>
