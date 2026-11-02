<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $classes = $user->isTeacher()
            ? $user->taughtClasses()->withCount(['students', 'assignments'])->latest()->get()
            : $user->enrolledClasses()->withCount(['assignments'])->with('teacher')->get();

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isTeacher(), 403);
        return view('classes.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isTeacher(), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'class_code' => 'required|string|max:20|unique:school_classes,class_code',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
        ]);

        $validated['teacher_id'] = auth()->id();
        $validated['invitation_code'] = strtoupper(Str::random(8));

        SchoolClass::create($validated);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dibuat!');
    }

    public function show(SchoolClass $class)
    {
        $user = auth()->user();
        if ($user->isTeacher()) {
            abort_unless($class->teacher_id === $user->id, 403);
        } else {
            abort_unless($class->students()->where('student_id', $user->id)->exists(), 403);
        }

        $class->load(['teacher', 'students', 'subjects', 'assignments' => function ($q) use ($user) {
            if ($user->isStudent()) $q->where('status', 'published');
            $q->latest();
        }]);

        return view('classes.show', compact('class'));
    }

    public function edit(SchoolClass $class)
    {
        abort_unless(auth()->user()->isTeacher() && $class->teacher_id === auth()->id(), 403);
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        abort_unless(auth()->user()->isTeacher() && $class->teacher_id === auth()->id(), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'class_code' => 'required|string|max:20|unique:school_classes,class_code,' . $class->id,
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'invitation_enabled' => 'nullable|boolean',
        ]);

        $validated['invitation_enabled'] = $request->boolean('invitation_enabled');
        $class->update($validated);
        return redirect()->route('classes.show', $class)->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy(SchoolClass $class)
    {
        abort_unless(auth()->user()->isTeacher() && $class->teacher_id === auth()->id(), 403);
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus!');
    }

    public function join(Request $request)
    {
        abort_unless(auth()->user()->isStudent(), 403);
        $request->validate(['invitation_code' => 'required|string']);

        $class = SchoolClass::where('invitation_code', trim($request->invitation_code))->first();

        if (! $class || ! $class->invitation_enabled) {
            return back()->with('error', 'Kode undangan tidak valid atau pendaftaran dinonaktifkan.');
        }

        if ($class->students()->where('student_id', auth()->id())->exists()) {
            return back()->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        $class->students()->attach(auth()->id(), ['joined_at' => now()]);
        return redirect()->route('classes.show', $class)->with('success', 'Berhasil bergabung dengan kelas!');
    }

    public function leave(SchoolClass $class)
    {
        abort_unless(auth()->user()->isStudent(), 403);
        $class->students()->detach(auth()->id());
        return redirect()->route('classes.index')->with('success', 'Anda telah keluar dari kelas.');
    }

    public function regenerateCode(SchoolClass $class)
    {
        abort_unless(auth()->user()->isTeacher() && $class->teacher_id === auth()->id(), 403);
        $class->update(['invitation_code' => strtoupper(Str::random(8))]);
        return back()->with('success', 'Kode undangan baru berhasil dibuat: ' . $class->invitation_code);
    }

    public function removeStudent(SchoolClass $class, User $student)
    {
        abort_unless(auth()->user()->isTeacher() && $class->teacher_id === auth()->id(), 403);
        $class->students()->detach($student->id);
        return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }
}

