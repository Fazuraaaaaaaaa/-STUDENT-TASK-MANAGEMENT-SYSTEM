<x-layouts.app title="Kelas Saya">
<div class="flex items-end justify-between mb-7">
    <div>
        <p class="text-sm text-indigo-600 font-semibold">Struktur Akademik</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Kelas Saya</h1>
        <p class="text-sm text-slate-500 mt-1">
            {{ auth()->user()->isTeacher() ? 'Kelola kelas Anda dan pantau siswa yang terdaftar.' : 'Lihat kelas tempat Anda terdaftar dan aktivitas pembelajarannya.' }}
        </p>
    </div>
    @if(auth()->user()->isTeacher())
        <a class="btn-primary" href="{{ route('classes.create') }}">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>Buat Kelas Baru
        </a>
    @else
        <div x-data="{open:false}" class="relative">
            <button @click="open=!open" class="btn-primary">
                <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>Gabung Kelas
            </button>
            <div x-show="open" @click.outside="open=false" class="absolute right-0 mt-2 w-80 rounded-2xl border border-slate-200 bg-white p-5 shadow-xl z-20">
                <form method="POST" action="{{ route('classes.join') }}">
                    @csrf
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Kode Undangan Kelas</label>
                    <input name="invitation_code" required placeholder="Contoh: TASKORA-A1" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <button class="btn-primary w-full text-xs py-2.5">Gabung Sekarang</button>
                </form>
            </div>
        </div>
    @endif
</div>

<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($classes as $class)
        <div class="card p-5 hover:border-indigo-200 transition">
            <div class="flex justify-between items-start">
                <div class="h-11 w-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-lucide="school" class="w-5 h-5"></i>
                </div>
                <span class="badge bg-indigo-50 text-indigo-700">{{ $class->class_code }}</span>
            </div>
            <h2 class="font-bold text-slate-800 mt-4 text-lg">{{ $class->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">Tahun Akademik: {{ $class->academic_year }}</p>
            @if(auth()->user()->isStudent())
                <p class="text-xs text-slate-400 mt-2">Wali Kelas/Guru: {{ $class->teacher->name }}</p>
            @endif
            <div class="border-t border-slate-100 mt-5 pt-3.5 flex justify-between items-center text-xs">
                <span class="text-slate-500">
                    <strong class="text-slate-800">{{ $class->students_count ?? $class->students->count() }}</strong> Siswa ·
                    <strong class="text-slate-800">{{ $class->assignments_count ?? $class->assignments->count() }}</strong> Tugas
                </span>
                <a href="{{ route('classes.show', $class) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                    Detail Kelas →
                </a>
            </div>
        </div>
    @empty
        <div class="card p-12 text-center col-span-full">
            <i data-lucide="school" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
            <h3 class="font-bold text-slate-700">Belum ada kelas</h3>
            <p class="text-xs text-slate-400 mt-1">
                {{ auth()->user()->isTeacher() ? 'Klik tombol Buat Kelas Baru untuk memulai.' : 'Masukkan kode undangan untuk bergabung ke kelas.' }}
            </p>
        </div>
    @endforelse
</div>
</x-layouts.app>
