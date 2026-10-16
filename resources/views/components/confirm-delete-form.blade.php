@props([
    'action',
    'method' => 'DELETE',
    'confirmMessage' => 'Yakin ingin menghapus item ini?',
    'buttonText' => 'Hapus',
    'buttonVariant' => 'danger',
    'class' => '',
])

<form 
    method="POST" 
    action="{{ $action }}"
    {{ $attributes->merge(['class' => 'inline ' . $class]) }}
    onsubmit="return handleDelete(this, '{{ addslashes($confirmMessage) }}')"
>
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <x-button 
        type="submit" 
        variant="{{ $buttonVariant }}"
        class="delete-btn"
    >
        {{ $buttonText }}
    </x-button>
</form>

@once
@push('scripts')
<script>
function handleDelete(form, message) {
    if (!confirm(message)) return false;
    
    const btn = form.querySelector('.delete-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-current border-r-transparent mr-2"></span>Menghapus...';
    
    return true;
}
</script>
@endpush
@endonce
