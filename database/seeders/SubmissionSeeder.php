<?php

namespace Database\Seeders;

use App\Models\{Assignment, AssignmentSubmission, Grade, SubmissionFile, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        [$s1, $s2, $s3] = User::where('role', 'student')->orderBy('id')->get()->all();
        $a1 = Assignment::where('title', 'like', '%Landing Page%')->first();
        $a2 = Assignment::where('title', 'like', '%Normalisasi%')->first();
        $a7 = Assignment::where('title', 'like', '%Linux%')->first();

        // Buat berkas fisik demo nyata di disk local (storage/app/private)
        $demoFiles = [
            'submissions/demo/landing_page_rafa.zip' => "TASKORA DEMO FILE\nBerkas demo landing page Rafa.",
            'submissions/demo/landing_page_siti.zip' => "TASKORA DEMO FILE\nBerkas demo landing page Siti.",
            'submissions/demo/normalisasi_rafa.pdf' => "%PDF-1.4\nTASKORA DEMO PDF - Normalisasi Rafa",
            'submissions/demo/normalisasi_siti.pdf' => "%PDF-1.4\nTASKORA DEMO PDF - Normalisasi Siti",
            'submissions/demo/normalisasi_dimas.pdf' => "%PDF-1.4\nTASKORA DEMO PDF - Normalisasi Dimas",
            'submissions/demo/linux_rafa.zip' => "TASKORA DEMO FILE\nBerkas demo dokumentasi Linux Rafa.",
            'submissions/demo/linux_dimas.pdf' => "%PDF-1.4\nTASKORA DEMO PDF - Dokumentasi Linux Dimas",
        ];
        foreach ($demoFiles as $path => $content) {
            Storage::disk('local')->put($path, $content);
        }

        // sub1: a1 - s1 (graded)
        $sub1 = AssignmentSubmission::create(['assignment_id' => $a1->id, 'student_id' => $s1->id, 'notes' => 'Sudah saya kerjakan lengkap dengan animasi.', 'status' => 'graded', 'submitted_at' => now()->subDays(5)]);
        SubmissionFile::create(['submission_id' => $sub1->id, 'file_path' => 'submissions/demo/landing_page_rafa.zip', 'original_filename' => 'landing_page_rafa.zip', 'mime_type' => 'application/zip', 'file_size' => 2048000, 'version_number' => 1]);
        Grade::create(['submission_id' => $sub1->id, 'graded_by' => $teacher->id, 'score' => 92, 'feedback' => 'Desain clean dan responsif. Animasi sangat smooth!', 'published_at' => now()->subDays(3)]);

        // sub2: a1 - s2 (under_review)
        $sub2 = AssignmentSubmission::create(['assignment_id' => $a1->id, 'student_id' => $s2->id, 'notes' => 'Tugas landing page saya pak.', 'status' => 'under_review', 'submitted_at' => now()->subDays(1)]);
        SubmissionFile::create(['submission_id' => $sub2->id, 'file_path' => 'submissions/demo/landing_page_siti.zip', 'original_filename' => 'landing_page_siti.zip', 'mime_type' => 'application/zip', 'file_size' => 1536000, 'version_number' => 1]);

        // sub3: a2 - s1 (submitted_late)
        $sub3 = AssignmentSubmission::create(['assignment_id' => $a2->id, 'student_id' => $s1->id, 'notes' => 'Terlambat, mohon maaf pak.', 'status' => 'submitted_late', 'submitted_at' => now()->subDays(3)]);
        SubmissionFile::create(['submission_id' => $sub3->id, 'file_path' => 'submissions/demo/normalisasi_rafa.pdf', 'original_filename' => 'normalisasi_rafa.pdf', 'mime_type' => 'application/pdf', 'file_size' => 512000, 'version_number' => 1]);

        // sub4: a2 - s2 (graded)
        $sub4 = AssignmentSubmission::create(['assignment_id' => $a2->id, 'student_id' => $s2->id, 'notes' => 'ERD draw.io terlampir.', 'status' => 'graded', 'submitted_at' => now()->subDays(7)]);
        SubmissionFile::create(['submission_id' => $sub4->id, 'file_path' => 'submissions/demo/normalisasi_siti.pdf', 'original_filename' => 'normalisasi_siti.pdf', 'mime_type' => 'application/pdf', 'file_size' => 768000, 'version_number' => 1]);
        Grade::create(['submission_id' => $sub4->id, 'graded_by' => $teacher->id, 'score' => 88, 'feedback' => 'ERD benar, normalisasi tepat. Penjelasan bisa lebih mendalam.', 'published_at' => now()->subDays(5)]);

        // sub5: a2 - s3 (revision_requested)
        $sub5 = AssignmentSubmission::create(['assignment_id' => $a2->id, 'student_id' => $s3->id, 'notes' => 'Tugas normalisasi saya.', 'status' => 'revision_requested', 'submitted_at' => now()->subDays(9)]);
        SubmissionFile::create(['submission_id' => $sub5->id, 'file_path' => 'submissions/demo/normalisasi_dimas.pdf', 'original_filename' => 'normalisasi_dimas.pdf', 'mime_type' => 'application/pdf', 'file_size' => 340000, 'version_number' => 1]);

        // sub6: a7 - s1 (graded)
        $sub6 = AssignmentSubmission::create(['assignment_id' => $a7->id, 'student_id' => $s1->id, 'notes' => 'Screenshot ada di dalam zip.', 'status' => 'graded', 'submitted_at' => now()->subDays(4)]);
        SubmissionFile::create(['submission_id' => $sub6->id, 'file_path' => 'submissions/demo/linux_rafa.zip', 'original_filename' => 'linux_rafa.zip', 'mime_type' => 'application/zip', 'file_size' => 5120000, 'version_number' => 1]);
        Grade::create(['submission_id' => $sub6->id, 'graded_by' => $teacher->id, 'score' => 95, 'feedback' => 'Dokumentasi instalasi sangat lengkap dan rapi.', 'published_at' => now()->subDays(2)]);

        // sub7: a7 - s3 (submitted)
        $sub7 = AssignmentSubmission::create(['assignment_id' => $a7->id, 'student_id' => $s3->id, 'notes' => 'Instalasi sukses.', 'status' => 'submitted', 'submitted_at' => now()->subDays(3)]);
        SubmissionFile::create(['submission_id' => $sub7->id, 'file_path' => 'submissions/demo/linux_dimas.pdf', 'original_filename' => 'linux_dimas.pdf', 'mime_type' => 'application/pdf', 'file_size' => 4096000, 'version_number' => 1]);
    }
}

