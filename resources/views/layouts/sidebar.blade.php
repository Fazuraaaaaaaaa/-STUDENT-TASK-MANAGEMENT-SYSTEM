<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[260px] border-r border-slate-200/80 bg-white flex flex-col transition-transform duration-300 lg:translate-x-0 lg:z-30">
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 shadow-sm"><i data-lucide="clipboard-check" class="h-5 w-5 text-white"></i></div>
        <div><h1 class="text-lg font-extrabold tracking-tight text-slate-900">TASKORA</h1><p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Manajemen Tugas</p></div>
    </div>
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @php $role = auth()->user()->role; @endphp
        <x-sidebar-link href="{{ route('dashboard') }}" icon="layout-dashboard" :active="request()->routeIs('dashboard')">Beranda</x-sidebar-link>
        <x-sidebar-link href="{{ route('classes.index') }}" icon="school" :active="request()->routeIs('classes.*')">Kelas Saya</x-sidebar-link>
        @if($role === 'teacher')
            <x-sidebar-link href="{{ route('subjects.index') }}" icon="book-open" :active="request()->routeIs('subjects.*')">Mata Pelajaran</x-sidebar-link>
            <x-sidebar-link href="{{ route('assignments.index') }}" icon="file-text" :active="request()->routeIs('assignments.*')">Daftar Tugas</x-sidebar-link>
            <x-sidebar-link href="{{ route('submissions.index') }}" icon="inbox" :active="request()->routeIs('submissions.*')">Pengumpulan</x-sidebar-link>
        @else
            <x-sidebar-link href="{{ route('student.assignments') }}" icon="file-text" :active="request()->routeIs('student.assignments*')">Tugas Saya</x-sidebar-link>
            <x-sidebar-link href="{{ route('student.grades') }}" icon="award" :active="request()->routeIs('student.grades')">Nilai Saya</x-sidebar-link>
        @endif
        <x-sidebar-link href="{{ route('calendar') }}" icon="calendar" :active="request()->routeIs('calendar')">Kalender</x-sidebar-link>
        <x-sidebar-link href="{{ route('notifications.index') }}" icon="bell" :active="request()->routeIs('notifications.*')">Notifikasi</x-sidebar-link>
        <x-sidebar-link href="{{ route('profile.edit') }}" icon="user" :active="request()->routeIs('profile.*')">Profil</x-sidebar-link>
    </nav>
    <div class="border-t border-slate-100 px-4 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="flex-1 min-w-0"><p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p><p class="text-[11px] text-slate-400 capitalize">{{ $role === 'teacher' ? 'Guru' : 'Siswa' }}</p></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button title="Keluar" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-red-500"><i data-lucide="log-out" class="h-4 w-4"></i></button></form>
        </div>
    </div>
</aside>