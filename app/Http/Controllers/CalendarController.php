<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Bulan aktif dari query string atau bulan saat ini
        $month = $request->query('month', now()->format('Y-m'));
        try {
            $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) {
            $currentMonth = now()->startOfMonth();
        }

        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        // Query tugas
        $query = Assignment::with(['subject', 'class', 'submissions' => function ($q) use ($user) {
            if ($user->isStudent()) {
                $q->where('student_id', $user->id);
            }
        }])->where('status', 'published');

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
            $subjects = Subject::where('teacher_id', $user->id)->get();
        } else {
            $classIds = $user->enrolledClasses()->pluck('school_classes.id');
            $query->whereIn('class_id', $classIds);
            $subjects = Subject::whereIn('class_id', $classIds)->get();
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $allAssignments = $query->orderBy('deadline_at')->get();

        // Tugas di bulan ini untuk grid
        $monthAssignments = $allAssignments->filter(function ($a) use ($currentMonth) {
            return $a->deadline_at->format('Y-m') === $currentMonth->format('Y-m');
        });

        // Struktur grid bulanan (42 cell: 6 minggu x 7 hari, mulai Senin)
        $startOfCalendar = $currentMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $currentMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $cursor = $startOfCalendar->copy();
        while ($cursor->lte($endOfCalendar)) {
            $dateString = $cursor->format('Y-m-d');
            $dayEvents = $monthAssignments->filter(fn($a) => $a->deadline_at->format('Y-m-d') === $dateString);

            $days[] = [
                'date' => $cursor->copy(),
                'isCurrentMonth' => $cursor->month === $currentMonth->month,
                'isToday' => $cursor->isToday(),
                'events' => $dayEvents,
            ];
            $cursor->addDay();
        }

        $viewMode = $request->query('view', 'month');

        return view('calendar.index', compact(
            'days',
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'allAssignments',
            'subjects',
            'viewMode'
        ));
    }
}

