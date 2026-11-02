<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TASKORA' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { brand: { DEFAULT: '#4f46e5', dark: '#3730a3' } }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .card { background-color: #ffffff; border-radius: 1rem; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .btn-primary { display: inline-flex; align-items: center; justify-content: center; border-radius: 0.75rem; background-color: #4f46e5; padding: 0.625rem 1rem; font-size: 0.875rem; font-weight: 600; color: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .btn-primary:hover { background-color: #4338ca; }
        .btn-secondary { display: inline-flex; align-items: center; justify-content: center; border-radius: 0.75rem; border: 1px solid #e2e8f0; background-color: #ffffff; padding: 0.625rem 1rem; font-size: 0.875rem; font-weight: 600; color: #334155; transition: all 0.2s; }
        .btn-secondary:hover { background-color: #f8fafc; }
        .badge { display: inline-flex; align-items: center; border-radius: 9999px; padding: 0.25rem 0.625rem; font-size: 0.75rem; font-weight: 600; }
    </style>
    <script defer src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">
<div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen=false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

@include('layouts.sidebar')

<div class="lg:pl-[260px] min-h-screen flex flex-col">
    @include('layouts.topbar')

    <main class="flex-1 p-4 lg:p-8 max-w-7xl w-full mx-auto">
        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 flex gap-2 items-center text-emerald-800 text-sm">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 flex gap-2 items-center text-rose-800 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        {{ $slot }}
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
</body>
</html>
