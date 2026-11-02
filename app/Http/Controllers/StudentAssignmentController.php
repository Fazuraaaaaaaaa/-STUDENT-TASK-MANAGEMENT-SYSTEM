<?php
namespace App\Http\Controllers;
use App\Models\Assignment;
use Illuminate\Http\Request;
class StudentAssignmentController extends Controller
{
 public function index(Request $r){$ids=auth()->user()->enrolledClasses()->pluck('school_classes.id');$q=Assignment::with(['subject','class','teacher'])->whereIn('class_id',$ids)->where('status','published');if($r->search)$q->where('title','like','%'.$r->search.'%');$assignments=$q->orderBy('deadline_at')->paginate(10)->withQueryString();return view('assignments.student-index',compact('assignments'));}
 public function show(Assignment $a){return app(\App\Http\Controllers\AssignmentController::class)->show($a);}
}
