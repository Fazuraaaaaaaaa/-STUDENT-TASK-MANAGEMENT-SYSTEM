<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TASKORA — Sign In</title>
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
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-slate-100 flex items-center justify-center p-4">
<div class="w-full max-w-md">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 shadow-lg mb-4">
            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">TASKORA</h1>
        <p class="mt-1 text-sm text-slate-500">Academic Task Management Platform</p>
    </div>

    {{-- Card --}}
    <div class="card p-8">
        <h2 class="text-xl font-bold text-slate-800 mb-1">Welcome back!</h2>
        <p class="text-sm text-slate-500 mb-6">Sign in to your account to continue</p>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex gap-2 items-start">
                <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-sm text-red-700">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200 @error('email') border-red-400 @enderror">
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" autocomplete="current-password" placeholder="••••••••" required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <button type="button" onclick="togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="remember" class="text-sm text-slate-600">Keep me signed in</label>
            </div>
            <button type="submit" class="btn-primary w-full h-12 text-base">Sign In</button>
        </form>

        <div class="mt-6 rounded-xl bg-slate-50 border border-slate-200 px-4 py-3.5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Demo Credentials</p>
            <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                <div><span class="font-medium text-indigo-600">Teacher:</span> teacher@taskora.test</div>
                <div><span class="font-medium text-indigo-600">Password:</span> password123</div>
                <div><span class="font-medium text-indigo-600">Student:</span> student1@taskora.test</div>
                <div><span class="font-medium text-indigo-600">Password:</span> password123</div>
            </div>
        </div>
    </div>
    <p class="text-center text-xs text-slate-400 mt-4">© {{ date('Y') }} TASKORA · All rights reserved</p>
</div>
<script>
    function togglePwd() {
        const inp = document.getElementById('password');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
