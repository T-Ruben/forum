<x-settings.aside>
    <x-slot:title>Personal Details</x-slot:title>

    <form action="{{ route('users.destroy', [$user, $user->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <x-forms.form-button onclick="return confirm('Are you sure?')">Delete Account</x-forms.form-button>
    </form>


</x-settings.aside>
