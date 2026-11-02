<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['class', 'subject'])->where('teacher_id', auth()->id());
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $assignments = $query->latest()->paginate(10)->withQueryString();
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        Gate::authorize('create', Assignment::class);
        $classes = auth()->user()->taughtClasses()->with('subjects')->get();
        $subjects = auth()->user()->subjects()->get();
        return view('assignments.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Assignment::class);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'deadline_at' => 'required|date',
            'max_score' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'description.required' => 'Deskripsi tugas wajib diisi.',
            'class_id.required' => 'Kelas tujuan wajib dipilih.',
            'class_id.exists' => 'Kelas yang dipilih tidak valid.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            'deadline_at.required' => 'Batas waktu pengumpulan wajib diisi.',
            'deadline_at.date' => 'Format batas waktu tidak valid.',
            'max_score.required' => 'Nilai maksimal wajib diisi.',
            'max_score.min' => 'Nilai maksimal minimal adalah 1.',
            'status.required' => 'Status publikasi wajib dipilih.',
        ]);

        // Verifikasi kelas milik guru yang sedang login
        abort_unless(
            SchoolClass::whereKey($data['class_id'])->where('teacher_id', auth()->id())->exists(),
            403,
            'Anda tidak memiliki akses ke kelas ini.'
        );

        // Verifikasi mata pelajaran milik guru
        abort_unless(
            Subject::whereKey($data['subject_id'])->where('teacher_id', auth()->id())->exists(),
            403,
            'Mata pelajaran tidak valid untuk akun Anda.'
        );

        $data['teacher_id'] = auth()->id();
        $data['allow_late_submission'] = $request->boolean('allow_late_submission');
        $data['allow_resubmission'] = $request->boolean('allow_resubmission');
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        Assignment::create($data);

        return redirect()->route('assignments.index')->with('success', 'Tugas baru berhasil dibuat dan ditambahkan!');
    }

    public function show(Assignment $assignment)
    {
        Gate::authorize('view', $assignment);

        $assignment->load(['class', 'subject', 'teacher', 'submissions.student', 'submissions.files', 'submissions.grade']);
        $submission = $assignment->submissions()->where('student_id', auth()->id())->first();

        return view('assignments.show', compact('assignment', 'submission'));
    }

    public function edit(Assignment $assignment)
    {
        Gate::authorize('update', $assignment);
        $classes = auth()->user()->taughtClasses()->get();
        $subjects = auth()->user()->subjects()->get();
        return view('assignments.edit', compact('assignment', 'classes', 'subjects'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        Gate::authorize('update', $assignment);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'deadline_at' => 'required|date',
            'max_score' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,archived',
        ]);

        $data['allow_late_submission'] = $request->boolean('allow_late_submission');
        $data['allow_resubmission'] = $request->boolean('allow_resubmission');
        if ($data['status'] === 'published' && ! $assignment->published_at) {
            $data['published_at'] = now();
        }

        $assignment->update($data);

        return redirect()->route('assignments.show', $assignment)->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment)
    {
        Gate::authorize('delete', $assignment);
        $assignment->update(['status' => 'archived']);
        $assignment->delete();
        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil diarsipkan/dihapus.');
    }

    public function publish(Assignment $assignment)
    {
        Gate::authorize('update', $assignment);
        $assignment->update(['status' => 'published', 'published_at' => now()]);
        return back()->with('success', 'Tugas berhasil dipublikasikan ke siswa.');
    }

    public function archive(Assignment $assignment)
    {
        Gate::authorize('update', $assignment);
        $assignment->update(['status' => 'archived']);
        return back()->with('success', 'Tugas berhasil diarsipkan.');
    }
}
