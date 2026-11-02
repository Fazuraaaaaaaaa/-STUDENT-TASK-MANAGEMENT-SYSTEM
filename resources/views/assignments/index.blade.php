<x-layouts.app title="Daftar Tugas">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
    <div>
        <p class="text-sm font-semibold text-indigo-600">Manajemen Penugasan</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Daftar Tugas</h1>
        <p class="text-sm text-slate-500 mt-1">Buat, kelola, dan publikasikan tugas untuk siswa.</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>Buat Tugas Baru
    </a>
</div>

<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
        <select name="status" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm bg-white">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
        </select>
        <button class="btn-primary">Cari & Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('assignments.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b">
            <tr>
                <th class="p-4">Judul Tugas</th>
                <th class="p-4">Mata Pelajaran & Kelas</th>
                <th class="p-4">Tenggat</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($assignments as $a)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="p-4">
                        <a href="{{ route('assignments.show', $a) }}" class="font-bold text-slate-900 hover:text-indigo-600 block line-clamp-1">
                            {{ $a->title }}
                        </a>
                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $a->description }}</p>
                    </td>
                    <td class="p-4">
                        <p class="font-medium text-slate-800">{{ $a->subject->name }}</p>
                        <p class="text-xs text-slate-400">{{ $a->class->name }}</p>
                    </td>
                    <td class="p-4">
                        <span class="font-medium {{ $a->isOverdue() ? 'text-red-600' : 'text-slate-700' }}">
                            {{ $a->deadline_at->format('d M Y, H:i') }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="badge {{ $a->status === 'published' ? 'bg-emerald-50 text-emerald-700' : ($a->status === 'draft' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                            {{ $a->status === 'published' ? 'Dipublikasikan' : ($a->status === 'draft' ? 'Draf' : 'Diarsipkan') }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('assignments.show', $a) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">
                                Lihat
                            </a>
                            <a href="{{ route('assignments.edit', $a) }}" class="text-slate-600 hover:text-slate-900 text-xs font-semibold">
                                Edit
                            </a>
                            @if($a->status === 'draft')
                                <form method="POST" action="{{ route('assignments.publish', $a) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-emerald-600 hover:text-emerald-800 text-xs font-semibold">
                                        Publikasikan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-slate-400">Belum ada tugas ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $assignments->links() }}</div>
</x-layouts.app>
