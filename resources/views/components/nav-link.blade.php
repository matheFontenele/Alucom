@props(['href', 'active', 'icon', 'label'])

<a href="{{ $href }}" 
    class="flex items-center gap-3 p-2.5 rounded-lg transition text-sm {{ $active ? 'bg-red-600 text-white shadow-md' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
    <i class="ph {{ $icon }} text-lg"></i>
    <span>{{ $label }}</span>
</a>