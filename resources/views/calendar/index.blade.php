<x-layouts.app title="Kalender Tugas">
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
    <div>
        <p class="text-sm font-semibold text-indigo-600">Jadwal Akademik</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Kalender Tugas</h1>
        <p class="text-sm text-slate-500 mt-1">Lihat tenggat tugas berdasarkan jadwal akademik.</p>
    </div>
    <form method="GET" class="flex items-center gap-2">
        <input type="hidden" name="month" value="{{ $currentMonth->format('Y-m') }}">
        <select name="subject_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
            <option value="">Semua Mata Pelajaran</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 p-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('calendar', ['month' => $prevMonth, 'subject_id' => request('subject_id')]) }}" class="btn-secondary py-2 px-3" aria-label="Bulan sebelumnya">←</a>
            <h2 class="text-lg font-bold text-slate-800 min-w-[170px] text-center">{{ $currentMonth->translatedFormat('F Y') }}</h2>
            <a href="{{ route('calendar', ['month' => $nextMonth, 'subject_id' => request('subject_id')]) }}" class="btn-secondary py-2 px-3" aria-label="Bulan berikutnya">→</a>
        </div>
        <a href="{{ route('calendar') }}" class="text-sm font-semibold text-indigo-600">Kembali ke bulan ini</a>
    </div>

    <div class="grid grid-cols-7 border-b border-slate-100 bg-slate-50 text-center text-xs font-bold uppercase text-slate-500">
        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $day)
            <div class="p-3">{{ $day }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7">
        @foreach($days as $day)
            <div class="min-h-[105px] border-b border-r border-slate-100 p-1.5 sm:p-2 {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-slate-50/70' }}">
                <div class="flex justify-end">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold {{ $day['isToday'] ? 'bg-indigo-600 text-white' : ($day['isCurrentMonth'] ? 'text-slate-700' : 'text-slate-300') }}">
                        {{ $day['date']->day }}
                    </span>
                </div>
                <div class="mt-1 space-y-1">
                    @foreach($day['events']->take(3) as $event)
                        <a href="{{ route('assignments.show', $event) }}" title="{{ $event->title }}" class="block truncate rounded-md bg-indigo-50 px-1.5 py-1 text-[10px] font-semibold text-indigo-700 hover:bg-indigo-100">
                            {{ $event->title }}
                        </a>
                    @endforeach
                    @if($day['events']->count() > 3)
                        <p class="text-[10px] text-slate-400">+ {{ $day['events']->count() - 3 }} tugas lain</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-6">
    <h2 class="font-bold text-slate-800 mb-3">Daftar Tenggat</h2>
    <div class="card divide-y divide-slate-100">
        @forelse($allAssignments as $assignment)
            <a href="{{ route('assignments.show', $assignment) }}" class="p-4 flex items-center gap-4 hover:bg-slate-50 transition">
                <div class="h-11 w-11 rounded-xl bg-indigo-50 text-center flex flex-col justify-center shrink-0"><span class="text-xs font-bold text-indigo-600">{{ $assignment->deadline_at->format('d') }}</span><span class="text-[10px] text-indigo-500 uppercase">{{ $assignment->deadline_at->format('M') }}</span></div>
                <div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800 truncate">{{ $assignment->title }}</p><p class="text-xs text-slate-400 mt-1">{{ $assignment->subject->name }} · {{ $assignment->class->name }}</p></div>
                <div class="text-right"><p class="text-sm font-semibold {{ $assignment->isOverdue() ? 'text-red-600' : 'text-slate-700' }}">{{ $assignment->deadline_at->format('H:i') }}</p><p class="text-xs text-slate-400">{{ $assignment->deadline_at->diffForHumans() }}</p></div>
            </a>
        @empty
            <div class="p-12 text-center text-slate-400">Tidak ada jadwal tugas.</div>
        @endforelse
    </div>
</div>
</x-layouts.app>