<?php
namespace App\Http\Controllers;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
 public function __invoke(Request $request) {
  $user=$request->user();
  if($user->isTeacher()) { $assignments=Assignment::where('teacher_id',$user->id)->withCount('submissions')->latest()->take(6)->get(); return view('dashboards.teacher',compact('assignments')); }
  $classIds=$user->enrolledClasses()->pluck('school_classes.id'); $assignments=Assignment::whereIn('class_id',$classIds)->where('status','published')->with(['subject','teacher'])->orderBy('deadline_at')->take(6)->get(); $submissionIds=$user->submissions()->pluck('assignment_id');
  return view('dashboards.student',compact('assignments','submissionIds'));
 }
}
