j<x-layouts.app title="Beranda Guru">
@php
$total=auth()->user()->teacherAssignments()->count();
$active=auth()->user()->teacherAssignments()->where('status','published')->count();
$submissions=auth()->user()->teacherAssignments()->withCount('submissions')->get()->sum('submissions_count');
$pending=\App\Models\AssignmentSubmission::whereHas('assignment',fn($q)=>$q->where('teacher_id',auth()->id()))->whereIn('status',['submitted','submitted_late','under_review'])->count();
@endphp
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">
    <div>
        <p class="text-sm font-medium text-indigo-600">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-slate-900 mt-1">Selamat datang kembali, {{ explode(' ',auth()->user()->name)[0] }}!</h1>
        <p class="text-sm text-slate-500 mt-1">Berikut ringkasan aktivitas kelas dan penugasan Anda.</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="btn-primary"><i data-lucide="plus" class="w-4 h-4 mr-2"></i>Buat Tugas Baru</a>
</div>

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-7">
@foreach([['Total Tugas',$total,'file-text','indigo'],['Tugas Aktif',$active,'circle-check','emerald'],['Total Pengumpulan',$submissions,'inbox','blue'],['Menunggu Penilaian',$pending,'clock-3','amber']] as [$label,$value,$icon,$color])
<div class="card p-5">
    <div class="flex justify-between">
        <div class="h-10 w-10 rounded-xl bg-{{$color}}-50 text-{{$color}}-600 flex items-center justify-center"><i data-lucide="{{$icon}}" class="w-5 h-5"></i></div>
        <span class="text-xs text-slate-400">Data riil</span>
    </div>
    <p class="mt-4 text-2xl font-extrabold text-slate-900">{{$value}}</p>
    <p class="text-sm font-medium text-slate-500 mt-1">{{$label}}</p>
</div>
@endforeach
</div>

<div class="grid xl:grid-cols-3 gap-6">
    <section class="card xl:col-span-2">
        <div class="flex justify-between items-center p-5 border-b border-slate-100">
            <div>
                <h2 class="font-bold text-slate-800">Tugas Terbaru</h2>
                <p class="text-xs text-slate-400 mt-1">Pantau perkembangan pengumpulan siswa</p>
            </div>
            <a href="{{route('assignments.index')}}" class="text-sm font-semibold text-indigo-600">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($assignments as $a)
                <div class="p-5 flex items-center gap-4">
                    <div class="h-10 w-10 shrink-0 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5"></i></div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-slate-800 truncate">{{$a->title}}</p>
                        <p class="text-xs text-slate-400 mt-1">{{$a->class->name}} · Batas {{$a->deadline_at->format('d M, H:i')}}</p>
                    </div>
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-bold text-slate-700">{{$a->submissions_count}} pengumpulan</p>
                        <span class="badge {{$a->status==='published'?'bg-emerald-50 text-emerald-700':'bg-amber-50 text-amber-700'}}">
                            {{ $a->status === 'published' ? 'Dipublikasikan' : ($a->status === 'draft' ? 'Draf' : 'Diarsipkan') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-slate-400">Belum ada tugas yang dibuat.</div>
            @endforelse
        </div>
    </section>

    <section class="card p-5">
        <h2 class="font-bold text-slate-800">Aksi Cepat</h2>
        <p class="text-xs text-slate-400 mt-1 mb-5">Akses alur kerja utama</p>
        <div class="space-y-3">
            <a href="{{route('assignments.create')}}" class="flex items-center gap-3 p-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 transition">
                <span class="h-9 w-9 flex items-center justify-center rounded-lg bg-indigo-600 text-white"><i data-lucide="plus" class="w-4 h-4"></i></span>
                <span class="text-sm font-semibold text-indigo-700">Buat tugas baru</span>
            </a>
            <a href="{{route('submissions.index')}}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                <span class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600"><i data-lucide="clipboard-check" class="w-4 h-4"></i></span>
                <span class="text-sm font-semibold text-slate-700">Periksa pengumpulan</span>
            </a>
            <a href="{{route('calendar')}}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                <span class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600"><i data-lucide="calendar" class="w-4 h-4"></i></span>
                <span class="text-sm font-semibold text-slate-700">Buka kalender</span>
            </a>
        </div>
    </section>
</div>
</x-layouts.app>