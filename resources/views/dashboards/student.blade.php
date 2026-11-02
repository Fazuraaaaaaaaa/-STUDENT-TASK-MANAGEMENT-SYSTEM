<x-layouts.app title="Beranda Siswa">
@php
$total=auth()->user()->enrolledClasses()->withCount('assignments')->get()->sum('assignments_count');
$submitted=$submissionIds->count();
$graded=auth()->user()->submissions()->where('status','graded')->count();
$pending=max(0,$total-$submitted);
$progress=$total?round(($submitted/$total)*100):0;
@endphp
<div class="mb-7">
    <p class="text-sm font-medium text-indigo-600">{{ now()->translatedFormat('l, d F Y') }}</p>
    <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-slate-900 mt-1">Selamat datang, {{ explode(' ',auth()->user()->name)[0] }}! <span class="text-2xl">👋</span></h1>
    <p class="text-sm text-slate-500 mt-1">Berikut ringkasan aktivitas akademik dan tugas Anda.</p>
</div>

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-7">
@foreach([['Total Tugas',$total,'file-text','indigo'],['Perlu Dikerjakan',$pending,'clock-3','amber'],['Sudah Dikumpulkan',$submitted,'check-circle-2','emerald'],['Sudah Dinilai',$graded,'award','purple']] as [$label,$value,$icon,$color])
<div class="card p-5">
    <div class="h-10 w-10 rounded-xl bg-{{$color}}-50 text-{{$color}}-600 flex items-center justify-center"><i data-lucide="{{$icon}}" class="w-5 h-5"></i></div>
    <p class="mt-4 text-2xl font-extrabold text-slate-900">{{$value}}</p>
    <p class="text-sm font-medium text-slate-500 mt-1">{{$label}}</p>
</div>
@endforeach
</div>

<div class="grid xl:grid-cols-3 gap-6">
    <section class="card xl:col-span-2">
        <div class="flex justify-between items-center p-5 border-b border-slate-100">
            <div>
                <h2 class="font-bold text-slate-800">Tugas Terdekat</h2>
                <p class="text-xs text-slate-400 mt-1">Selesaikan sebelum batas waktu berakhir</p>
            </div>
            <a href="{{route('student.assignments')}}" class="text-sm font-semibold text-indigo-600">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($assignments as $a)
                <a href="{{route('assignments.show',$a)}}" class="p-5 flex items-center gap-4 hover:bg-slate-50 transition block">
                    <div class="h-10 w-10 shrink-0 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5"></i></div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-slate-800 truncate">{{$a->title}}</p>
                        <p class="text-xs text-slate-400 mt-1">{{$a->subject->name}} · {{$a->teacher->name}}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold {{$a->isOverdue()?'text-red-600':'text-slate-700'}}">{{$a->deadline_at->format('d M')}}</p>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $submissionIds->contains($a->id) ? 'Sudah Dikumpulkan' : ($a->isOverdue() ? 'Terlewat' : 'Mendekati Batas') }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center">
                    <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                    <p class="text-sm text-slate-400">Belum ada tugas yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="card p-5">
        <h2 class="font-bold text-slate-800">Progres Anda</h2>
        <p class="text-xs text-slate-400 mt-1">Persentase penyelesaian tugas</p>
        <div class="mt-7 flex items-center justify-center">
            <div class="relative h-36 w-36 rounded-full" style="background: conic-gradient(#4f46e5 {{$progress}}%, #e0e7ff 0)">
                <div class="absolute inset-3 rounded-full bg-white flex flex-col items-center justify-center">
                    <span class="text-3xl font-extrabold text-slate-800">{{$progress}}%</span>
                    <span class="text-xs text-slate-400">selesai</span>
                </div>
            </div>
        </div>
        <div class="mt-7 space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Total tugas</span><span class="font-bold text-slate-800">{{$total}}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Sudah dikumpulkan</span><span class="font-bold text-emerald-600">{{$submitted}}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Belum dikumpulkan</span><span class="font-bold text-amber-600">{{$pending}}</span></div>
        </div>
    </section>
</div>
</x-layouts.app>