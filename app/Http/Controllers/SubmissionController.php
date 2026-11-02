<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SubmissionFile;
use App\Models\TaskNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->isTeacher(), 403);

        $query = AssignmentSubmission::with(['assignment.class', 'assignment.subject', 'student', 'grade', 'files'])
            ->whereHas('assignment', fn($q) => $q->where('teacher_id', auth()->id()));

        if ($request->filled('assignment')) {
            $query->where('assignment_id', $request->assignment);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest('submitted_at')->paginate(12)->withQueryString();
        $assignments = auth()->user()->teacherAssignments()->latest()->get();

        return view('submissions.index', compact('submissions', 'assignments'));
    }

    public function show(AssignmentSubmission $submission)
    {
        Gate::authorize('view', $submission);

        $submission->load(['assignment.subject', 'assignment.class', 'student', 'files', 'grade.histories']);
        return view('submissions.show', compact('submission'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        abort_unless(auth()->user()->isStudent(), 403);

        // Verifikasi keanggotaan kelas
        abort_unless($assignment->class->students()->whereKey(auth()->id())->exists(), 403);

        $isOverdue = $assignment->isOverdue();
        if ($isOverdue && ! $assignment->allow_late_submission) {
            return back()->with('error', 'Batas waktu pengumpulan telah berakhir dan tugas ini tidak menerima keterlambatan.');
        }

        $existing = $assignment->submissions()->where('student_id', auth()->id())->first();
        if ($existing && ! $assignment->allow_resubmission && $existing->status !== 'revision_requested') {
            return back()->with('error', 'Tugas ini tidak mengizinkan pengumpulan ulang.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,zip',
        ], [
            'file.required' => 'Berkas tugas wajib diunggah.',
            'file.mimes' => 'Format berkas harus berupa pdf, doc, docx, ppt, pptx, jpg, jpeg, png, atau zip.',
            'file.max' => 'Ukuran berkas maksimal adalah 10 MB.',
        ]);

        DB::transaction(function () use ($request, $assignment, $isOverdue, $existing) {
            $status = $isOverdue ? 'submitted_late' : 'submitted';

            $submission = $existing ?? AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => auth()->id(),
                'status' => $status,
                'notes' => $request->notes,
                'submitted_at' => now(),
            ]);

            if ($existing) {
                $submission->update([
                    'status' => $status,
                    'notes' => $request->notes ?? $submission->notes,
                    'submitted_at' => now(),
                ]);
            }

            $version = ($submission->files()->max('version_number') ?? 0) + 1;
            $file = $request->file('file');
            $path = $file->store('submissions/' . $assignment->id, 'local');

            SubmissionFile::create([
                'submission_id' => $submission->id,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'version_number' => $version,
            ]);

            TaskNotification::create([
                'user_id' => $assignment->teacher_id,
                'type' => $isOverdue ? 'submission_late' : 'submission_received',
                'title' => ($isOverdue ? 'Pengumpulan Terlambat: ' : 'Pengumpulan Baru: ') . auth()->user()->name,
                'message' => auth()->user()->name . ' telah mengumpulkan tugas ' . $assignment->title . '.',
                'related_type' => Assignment::class,
                'related_id' => $assignment->id,
            ]);
        });

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function resubmit(Request $request, AssignmentSubmission $submission)
    {
        abort_unless($submission->student_id === auth()->id(), 403);
        return $this->store($request, $submission->assignment);
    }

    public function download(AssignmentSubmission $submission)
    {
        Gate::authorize('view', $submission);

        $file = $submission->files()->latest('version_number')->firstOrFail();

        if (Storage::disk('local')->exists($file->file_path)) {
            return Storage::disk('local')->download($file->file_path, $file->original_filename);
        }

        // Fallback demo file if demo seeder path
        return response("Demo file contents for {$file->original_filename}", 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $file->original_filename . '"',
        ]);
    }
}

