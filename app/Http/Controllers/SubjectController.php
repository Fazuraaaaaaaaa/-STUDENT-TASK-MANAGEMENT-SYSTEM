<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index() { abort_unless(auth()->user()->isTeacher(), 403); $subjects = auth()->user()->subjects()->with('class')->latest()->paginate(15); return view('subjects.index', compact('subjects')); }
    public function create() { abort_unless(auth()->user()->isTeacher(), 403); return view('subjects.create', ['classes' => auth()->user()->taughtClasses]); }
    public function store(Request $request) { abort_unless(auth()->user()->isTeacher(), 403); $data=$request->validate(['name'=>'required|string|max:100','subject_code'=>'nullable|string|max:20','description'=>'nullable|string','class_id'=>'required|exists:school_classes,id']); abort_unless(SchoolClass::whereKey($data['class_id'])->where('teacher_id',auth()->id())->exists(),403); Subject::create($data+['teacher_id'=>auth()->id()]); return redirect()->route('subjects.index')->with('success','Mata pelajaran berhasil dibuat.'); }
    public function edit(Subject $subject) { abort_unless($subject->teacher_id===auth()->id(),403); return view('subjects.edit', ['subject'=>$subject,'classes'=>auth()->user()->taughtClasses]); }
    public function update(Request $request, Subject $subject) { abort_unless($subject->teacher_id===auth()->id(),403); $data=$request->validate(['name'=>'required|string|max:100','subject_code'=>'nullable|string|max:20','description'=>'nullable|string','class_id'=>'required|exists:school_classes,id']); abort_unless(SchoolClass::whereKey($data['class_id'])->where('teacher_id',auth()->id())->exists(),403); $subject->update($data); return redirect()->route('subjects.index')->with('success','Mata pelajaran berhasil diperbarui.'); }
    public function destroy(Subject $subject) { abort_unless($subject->teacher_id===auth()->id(),403); abort_if($subject->assignments()->exists(),409,'Mata pelajaran masih digunakan oleh assignment.'); $subject->delete(); return back()->with('success','Mata pelajaran berhasil dihapus.'); }
}
