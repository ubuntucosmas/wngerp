@php
    $user = auth()->user();
@endphp

@if($user && $user->hasRole('super-admin') && $user->level == 5)
    <form method="POST" action="{{ $action }}" onsubmit="return confirm('Are you sure you want to delete this?');" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            {{ $slot ?: 'Delete' }}
        </button>
    </form>
@endif