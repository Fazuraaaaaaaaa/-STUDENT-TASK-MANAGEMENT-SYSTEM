<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Grade;
use App\Models\GradeHistory;
use App\Models\TaskNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class GradeController extends Controller
{
    public function store(Request $request, AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment->teacher_id === auth()->id(), 403);
        // Otorisasi policy juga digunakan oleh alur grading.
        Gate::authorize('grade', $submission);

        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:' . $submission->assignment->max_score,
            'feedback' => 'nullable|string|max:2000',
            'action' => 'required|in:grade,revision',
        ]);

        $isRevision = $validated['action'] === 'revision';
        $newStatus = $isRevision ? 'revision_requested' : 'graded';

        DB::transaction(function () use ($submission, $validated, $isRevision, $newStatus) {
            $existingGrade = $submission->grade;

            if ($existingGrade) {
                GradeHistory::create([
                    'grade_id' => $existingGrade->id,
                    'previous_score' => $existingGrade->score,
                    'new_score' => $validated['score'],
                    'previous_feedback' => $existingGrade->feedback,
                    'new_feedback' => $validated['feedback'] ?? null,
                    'changed_by' => auth()->id(),
                ]);

                $existingGrade->update([
                    'score' => $validated['score'],
                    'feedback' => $validated['feedback'] ?? null,
                    'published_at' => $isRevision ? null : now(),
                ]);
            } else {
                Grade::create([
                    'submission_id' => $submission->id,
                    'graded_by' => auth()->id(),
                    'score' => $validated['score'],
                    'feedback' => $validated['feedback'] ?? null,
                    'published_at' => $isRevision ? null : now(),
                ]);
            }

            $submission->update(['status' => $newStatus]);

            TaskNotification::create([
                'user_id' => $submission->student_id,
                'type' => $isRevision ? 'revision_requested' : 'assignment_graded',
                'title' => $isRevision ? 'Revisi Diminta: ' . $submission->assignment->title : 'Nilai Diterbitkan: ' . $submission->assignment->title,
                'message' => $isRevision
                    ? 'Guru meminta revisi: ' . ($validated['feedback'] ?? 'Mohon periksa kembali tugas Anda.')
                    : 'Nilai Anda: ' . $validated['score'] . '/' . $submission->assignment->max_score,
                'related_type' => Assignment::class,
                'related_id' => $submission->assignment_id,
            ]);
        });

        $msg = $isRevision ? 'Permintaan revisi berhasil dikirim ke siswa.' : 'Nilai dan umpan balik berhasil disimpan!';
        return back()->with('success', $msg);
    }

    public function studentIndex()
    {
        $grades = auth()->user()->submissions()
            ->with(['assignment.subject', 'assignment.class', 'grade'])
            ->whereHas('grade', fn($q) => $q->whereNotNull('published_at'))
            ->latest('submitted_at')
            ->paginate(12);

        return view('grades.index', compact('grades'));
    }
}

