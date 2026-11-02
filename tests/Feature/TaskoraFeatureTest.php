<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolClass;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskoraFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_teacher_can_login_and_view_dashboard(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $response = $this->post('/login', ['email' => $teacher->email, 'password' => 'password123']);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($teacher);
        $this->actingAs($teacher)->get('/dashboard')->assertStatus(200);
    }

    public function test_student_can_login_and_view_dashboard(): void
    {
        $student = User::where('role', 'student')->first();
        $this->actingAs($student)->get('/dashboard')->assertStatus(200);
    }

    public function test_role_authorization_is_enforced(): void
    {
        $student = User::where('role', 'student')->first();
        $this->actingAs($student)->get('/assignments/create')->assertStatus(403);
        $this->actingAs($student)->get('/submissions')->assertStatus(403);
        $this->actingAs($student)->get('/subjects')->assertStatus(403);

        $teacher = User::where('role', 'teacher')->first();
        $this->actingAs($teacher)->get('/my-assignments')->assertStatus(403);
        $this->actingAs($teacher)->get('/my-grades')->assertStatus(403);
    }

    public function test_teacher_can_view_submissions_and_grade(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $submission = AssignmentSubmission::whereHas('assignment', fn($q) => $q->where('teacher_id', $teacher->id))->first();

        $this->actingAs($teacher)->get('/submissions')->assertStatus(200);
        $this->actingAs($teacher)->get('/submissions/' . $submission->id)->assertStatus(200);

        $gradeResponse = $this->actingAs($teacher)->post('/grades/' . $submission->id, [
            'score' => 90,
            'feedback' => 'Bagus sekali, terus tingkatkan pemahaman!',
            'action' => 'grade',
        ]);
        $gradeResponse->assertSessionHas('success');
    }

    public function test_student_and_teacher_can_download_submission_file(): void
    {
        $submission = AssignmentSubmission::first();
        $teacher = $submission->assignment->teacher;
        $student = $submission->student;

        $this->actingAs($teacher)->get('/submissions/' . $submission->id . '/download')->assertStatus(200);
        $this->actingAs($student)->get('/submissions/' . $submission->id . '/download')->assertStatus(200);

        $otherStudent = User::where('role', 'student')->where('id', '!=', $student->id)->first();
        $this->actingAs($otherStudent)->get('/submissions/' . $submission->id . '/download')->assertStatus(403);
    }

    public function test_student_can_join_class_with_code(): void
    {
        $student = User::where('role', 'student')->first();
        $newClass = SchoolClass::create([
            'teacher_id' => User::where('role', 'teacher')->first()->id,
            'name' => 'Kelas Baru Uji',
            'class_code' => 'NEW-TEST-1',
            'academic_year' => '2026/2027',
            'invitation_code' => 'TESTCODE',
            'invitation_enabled' => true,
        ]);

        $join = $this->actingAs($student)->post('/classes/join', ['invitation_code' => 'TESTCODE']);
        $join->assertRedirect('/classes/' . $newClass->id);
        $this->assertTrue($newClass->students()->where('student_id', $student->id)->exists());

        $invalid = $this->actingAs($student)->post('/classes/join', ['invitation_code' => 'SALAH123']);
        $invalid->assertSessionHas('error');
    }

    public function test_student_can_submit_assignment_with_file(): void
    {
        Storage::fake('local');
        $student = User::where('role', 'student')->first();
        $assignment = Assignment::where('status', 'published')
            ->where('deadline_at', '>', now())
            ->whereHas('class.students', fn($q) => $q->where('student_id', $student->id))
            ->first();

        AssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', $student->id)->delete();
        $file = UploadedFile::fake()->create('tugas_saya.pdf', 500, 'application/pdf');

        $response = $this->actingAs($student)->post('/assignments/' . $assignment->id . '/submit', [
            'file' => $file,
            'notes' => 'Catatan pengerjaan tugas oleh siswa.',
        ]);

        $response->assertSessionHas('success');
    }

    public function test_deadline_reminder_command_runs_successfully(): void
    {
        $this->assertSame(0, Artisan::call('taskora:send-deadline-reminders'));
    }

    public function test_calendar_and_notifications_work_for_both_roles(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $this->actingAs($teacher)->get('/calendar')->assertStatus(200);
        $this->actingAs($teacher)->get('/notifications')->assertStatus(200);

        $student = User::where('role', 'student')->first();
        $this->actingAs($student)->get('/calendar')->assertStatus(200);
        $this->actingAs($student)->get('/notifications')->assertStatus(200);
    }
}


