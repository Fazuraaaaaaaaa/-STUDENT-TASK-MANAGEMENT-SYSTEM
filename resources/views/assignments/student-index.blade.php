<x-layouts.app title="Tugas Saya">
<div class="mb-7">
    <p class="text-sm font-semibold text-indigo-600">Kelas Terdaftar</p>
    <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Tugas Saya</h1>
    <p class="text-sm text-slate-500 mt-1">Lihat dan kumpulkan tugas dari kelas yang Anda ikuti.</p>
</div>

<div class="card p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
        <button class="btn-primary">Cari Tugas</button>
        @if(request('search'))
            <a href="{{ route('student.assignments') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($assignments as $a)
        <a href="{{ route('assignments.show', $a) }}" class="card p-5 hover:border-indigo-300 transition group flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-3">
                    <span class="badge bg-indigo-50 text-indigo-700">{{ $a->subject->name }}</span>
                    <span class="text-xs font-semibold {{ $a->isOverdue() ? 'text-red-500' : 'text-slate-400' }}">
                        {{ $a->deadline_at->diffForHumans() }}
                    </span>
                </div>
                <h2 class="font-bold text-slate-900 group-hover:text-indigo-600 transition">{{ $a->title }}</h2>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $a->description }}</p>
            </div>
            <div class="border-t border-slate-100 mt-5 pt-3 flex justify-between items-center text-xs">
                <span class="text-slate-400">Guru: {{ $a->teacher->name }}</span>
                <span class="font-semibold text-indigo-600 group-hover:translate-x-0.5 transition">Lihat detail →</span>
            </div>
        </a>
    @empty
        <div class="card p-12 text-center col-span-full">
            <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
            <p class="text-sm text-slate-400">Belum ada tugas aktif untuk kelas Anda.</p>
        </div>
    @endforelse
</div>
<div class="mt-4">{{ $assignments->links() }}</div>
</x-layouts.app>
