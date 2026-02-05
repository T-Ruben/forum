@props(['action', 'model'])

@can('delete', $model)
    <form action="{{ $action }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Confirm to delete post.')"
                {{ $attributes->merge(['class' => 'cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold']) }}>
                Delete
        </button>
    </form>
@endcan
