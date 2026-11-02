<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\TaskNotification;
use Illuminate\Console\Command;

class SendDeadlineReminders extends Command
{
    protected $signature = 'taskora:send-deadline-reminders';
    protected $description = 'Kirim notifikasi pengingat batas waktu tugas kepada siswa yang belum mengumpulkan';

    public function handle()
    {
        $now = now();
        $in24Hours = $now->copy()->addHours(24);

        // Ambil tugas yang dipublikasikan dengan deadline dalam rentang 24 jam ke depan
        $assignments = Assignment::where('status', 'published')
            ->where('deadline_at', '>', $now)
            ->where('deadline_at', '<=', $in24Hours)
            ->with(['class.students', 'submissions'])
            ->get();

        $sentCount = 0;

        foreach ($assignments as $assignment) {
            $submittedStudentIds = $assignment->submissions->pluck('student_id')->all();

            foreach ($assignment->class->students as $student) {
                // Jika sudah mengumpulkan, lewati
                if (in_array($student->id, $submittedStudentIds, true)) {
                    continue;
                }

                // Cek apakah sudah dikirimi reminder untuk tugas ini dalam 12 jam terakhir
                $alreadyNotified = TaskNotification::where('user_id', $student->id)
                    ->where('type', 'deadline_reminder')
                    ->where('related_id', $assignment->id)
                    ->where('created_at', '>=', now()->subHours(12))
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                $diff = $assignment->deadline_at->diffForHumans();

                TaskNotification::create([
                    'user_id' => $student->id,
                    'type' => 'deadline_reminder',
                    'title' => 'Pengingat Tenggat: ' . $assignment->title,
                    'message' => 'Tugas ' . $assignment->title . ' akan berakhir ' . $diff . '. Jangan lupa untuk mengumpulkan tepat waktu.',
                    'related_type' => Assignment::class,
                    'related_id' => $assignment->id,
                ]);

                $sentCount++;
            }
        }

        $this->info("Berhasil mengirim {$sentCount} pengingat tenggat tugas.");
        return Command::SUCCESS;
    }
}

