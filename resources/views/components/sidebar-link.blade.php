@props(['href', 'icon' => null, 'active' => false])
<a href="{{ $href }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
    @if($icon) <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i> @endif
    <span>{{ $slot }}</span>
</a>
