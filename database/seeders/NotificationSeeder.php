<?php
namespace Database\Seeders;
use App\Models\{Assignment, TaskNotification, User};
use Illuminate\Database\Seeder;
class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $teacher=User::where('role','teacher')->first(); [$s1,$s2,$s3]=User::where('role','student')->orderBy('id')->get()->all();
        $a1=Assignment::where('title','like','%Landing Page%')->first(); $a2=Assignment::where('title','like','%Normalisasi%')->first(); $a4=Assignment::where('title','like','%Essay%')->first(); $a7=Assignment::where('title','like','%Linux%')->first();
        $this->n($s1,'new_assignment','Tugas Baru: Landing Page Modern','Tugas baru di kelas XII RPL 1.',$a1,now()->subDays(14));
        $this->n($s2,'new_assignment','Tugas Baru: Landing Page Modern','Tugas baru di kelas XII RPL 1.',$a1,now()->subDays(14));
        $this->n($s3,'new_assignment','Tugas Baru: Landing Page Modern','Tugas baru di kelas XII RPL 1.',$a1,now()->subDays(14));
        $this->n($s1,'assignment_graded','Nilai: Landing Page Modern – 92/100','Tugas Anda telah dinilai.',$a1,now()->subDays(3));
        $this->n($s2,'assignment_graded','Nilai: Normalisasi Database – 88/100','Tugas Anda telah dinilai.',$a2,now()->subDays(5),true);
        $this->n($s3,'revision_requested','Revisi Diminta: Normalisasi Database','Mohon revisi bagian 3NF.',$a2,now()->subDays(6));
        $this->n($teacher,'submission_received','Submisi: Rafa Ardian – Landing Page','Rafa Ardian telah mengumpulkan tugas.',$a1,now()->subDays(5),true);
        $this->n($s1,'deadline_reminder','Deadline Besok: Essay Technology','Tugas Essay deadline besok!',$a4,now()->subHours(20));
        $this->n($s1,'assignment_graded','Nilai: Instalasi Linux – 95/100','Tugas Linux telah dinilai.',$a7,now()->subDays(2));
    }
    private function n($u,$type,$title,$message,$assignment,$time,$read=false): void { TaskNotification::create(['user_id'=>$u->id,'type'=>$type,'title'=>$title,'message'=>$message,'related_type'=>Assignment::class,'related_id'=>$assignment->id,'read_at'=>$read?$time:null,'created_at'=>$time,'updated_at'=>$time]); }
}
