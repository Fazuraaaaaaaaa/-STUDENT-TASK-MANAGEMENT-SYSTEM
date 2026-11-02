<x-layouts.app title="Submissions Review">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm font-semibold text-indigo-600">Submissions Management</p>
        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Student Submissions</h1>
        <p class="text-sm text-slate-500 mt-1">Review student work, grade submissions, and provide feedback.</p>
    </div>
</div>

<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <select name="assignment" class="flex-1 rounded-xl border px-3 py-2 text-sm bg-white">
            <option value="">All Assignments</option>
            @foreach($assignments as $a)
                <option value="{{ $a->id }}" {{ request('assignment') == $a->id ? 'selected' : '' }}>{{ $a->title }} ({{ $a->class->name }})</option>
            @endforeach
        </select>
        <select name="status" class="rounded-xl border px-3 py-2 text-sm bg-white">
            <option value="">All Statuses</option>
            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="submitted_late" {{ request('status') === 'submitted_late' ? 'selected' : '' }}>Submitted Late</option>
            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
            <option value="graded" {{ request('status') === 'graded' ? 'selected' : '' }}>Graded</option>
        </select>
        <button class="btn-primary">Filter</button>
        @if(request()->hasAny(['assignment', 'status']))
            <a href="{{ route('submissions.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b">
            <tr>
                <th class="p-4">Student</th>
                <th class="p-4">Assignment</th>
                <th class="p-4">Submitted At</th>
                <th class="p-4">Status</th>
                <th class="p-4">Grade</th>
                <th class="p-4 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($submissions as $sub)
                <tr class="hover:bg-slate-50/50">
                    <td class="p-4">
                        <p class="font-bold text-slate-800">{{ $sub->student->name }}</p>
                        <p class="text-xs text-slate-400">{{ $sub->student->student_number ?? $sub->student->email }}</p>
                    </td>
                    <td class="p-4">
                        <a href="{{ route('assignments.show', $sub->assignment) }}" class="font-semibold text-slate-800 hover:text-indigo-600">{{ $sub->assignment->title }}</a>
                        <p class="text-xs text-slate-400">{{ $sub->assignment->class->name }} · {{ $sub->assignment->subject->name }}</p>
                    </td>
                    <td class="p-4">
                        <span class="text-xs text-slate-700">{{ $sub->submitted_at ? $sub->submitted_at->format('d M Y, H:i') : '-' }}</span>
                        @if($sub->status === 'submitted_late') <span class="block text-[10px] text-red-500 font-semibold">Late</span> @endif
                    </td>
                    <td class="p-4">
                        <span class="badge {{ $sub->status === 'graded' ? 'bg-emerald-50 text-emerald-700' : ($sub->status === 'submitted_late' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700') }}">{{ str_replace('_', ' ', ucfirst($sub->status)) }}</span>
                    </td>
                    <td class="p-4">
                        @if($sub->grade && $sub->grade->published_at)
                            <span class="font-extrabold text-slate-900">{{ $sub->grade->score }}</span><span class="text-xs text-slate-400">/{{ $sub->assignment->max_score }}</span>
                        @else
                            <span class="text-xs text-slate-400 italic">Pending</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('submissions.show', $sub) }}" class="btn-primary text-xs py-1.5 px-3">Review & Grade</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-10 text-center text-slate-400">No submissions found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $submissions->links() }}</div>
</x-layouts.app>