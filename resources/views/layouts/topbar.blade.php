<header class="sticky top-0 z-20 h-16 border-b border-slate-200/80 bg-white/80 backdrop-blur flex items-center px-4 lg:px-8 gap-4">
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden rounded-lg p-2 text-slate-500 hover:bg-slate-100"><i data-lucide="menu" class="h-5 w-5"></i></button>
    <h2 class="text-lg font-bold text-slate-800">{{ $title ?? 'Beranda' }}</h2>
    <div class="flex-1"></div>
    @php $unread = auth()->user()->unreadNotificationsCount(); @endphp
    <a href="{{ route('notifications.index') }}" title="Notifikasi" class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100"><i data-lucide="bell" class="h-5 w-5"></i>@if($unread > 0)<span class="absolute top-1 right-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ $unread }}</span>@endif</a>
    <div class="hidden sm:flex items-center gap-3 border-l border-slate-200 pl-4"><div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div><p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p><p class="text-[11px] text-slate-400 capitalize">{{ auth()->user()->role === 'teacher' ? 'Guru' : 'Siswa' }}</p></div></div>
</header>