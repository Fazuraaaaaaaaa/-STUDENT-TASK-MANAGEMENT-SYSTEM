<?php
namespace Database\Seeders;
use App\Models\{Assignment, SchoolClass, Subject, User};
use Illuminate\Database\Seeder;
class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        [$teacher,$s1,$s2,$s3] = [User::where('role','teacher')->first(), ...User::where('role','student')->orderBy('id')->get()->all()];
        $c1 = SchoolClass::create(['teacher_id'=>$teacher->id,'name'=>'XII RPL 1','class_code'=>'XII-RPL-1','academic_year'=>'2026/2027','description'=>'Rekayasa Perangkat Lunak 12','invitation_code'=>'TASKORA-A1']);
        $c2 = SchoolClass::create(['teacher_id'=>$teacher->id,'name'=>'XI TKJ 2','class_code'=>'XI-TKJ-2','academic_year'=>'2026/2027','description'=>'Teknik Komputer Jaringan 11','invitation_code'=>'TASKORA-B2']);
        $c1->students()->attach([$s1->id,$s2->id,$s3->id],['joined_at'=>now()->subDays(30)]);
        $c2->students()->attach([$s1->id,$s3->id],['joined_at'=>now()->subDays(25)]);

        $pW=Subject::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'name'=>'Pemrograman Web','subject_code'=>'WEB']);
        $pD=Subject::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'name'=>'Basis Data','subject_code'=>'DB']);
        $pM=Subject::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'name'=>'Matematika','subject_code'=>'MTK']);
        $pE=Subject::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'name'=>'Bahasa Inggris','subject_code'=>'ENG']);
        $pN=Subject::create(['teacher_id'=>$teacher->id,'class_id'=>$c2->id,'name'=>'Jaringan Komputer','subject_code'=>'NET']);
        $pO=Subject::create(['teacher_id'=>$teacher->id,'class_id'=>$c2->id,'name'=>'Sistem Operasi','subject_code'=>'OS']);

        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'subject_id'=>$pW->id,'title'=>'Membuat Landing Page Modern','description'=>'Buat landing page responsif.','instructions'=>"1. HTML5.\n2. CSS Grid.\n3. Responsif.",'published_at'=>now()->subDays(14),'deadline_at'=>now()->addDays(3),'max_score'=>100,'allow_late_submission'=>true,'allow_resubmission'=>true,'status'=>'published']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'subject_id'=>$pD->id,'title'=>'Normalisasi Database Perpustakaan','description'=>'Rancang ERD dan normalisasi 1NF-3NF.','instructions'=>'Dokumentasikan ERD dan 3NF.','published_at'=>now()->subDays(20),'deadline_at'=>now()->subDays(5),'max_score'=>100,'status'=>'published']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'subject_id'=>$pM->id,'title'=>'Soal Matriks dan Determinan','description'=>'20 Soal Operasi Matriks.','instructions'=>'Tunjukkan langkah.','published_at'=>now()->subDays(7),'deadline_at'=>now()->addDays(7),'max_score'=>100,'allow_late_submission'=>true,'status'=>'published']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'subject_id'=>$pE->id,'title'=>'Essay: Technology in Education','description'=>'500-word essay about edtech.','instructions'=>'Min 500 words.','published_at'=>now()->subDays(10),'deadline_at'=>now()->addDays(1),'max_score'=>100,'allow_late_submission'=>true,'allow_resubmission'=>true,'status'=>'published']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'subject_id'=>$pW->id,'title'=>'Aplikasi To-Do List React','description'=>'Aplikasi to-do CRUD.','instructions'=>'Gunakan React hooks.','deadline_at'=>now()->addDays(14),'max_score'=>100,'status'=>'draft']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c2->id,'subject_id'=>$pN->id,'title'=>'Konfigurasi VLAN','description'=>'Cisco Packet Tracer VLAN.','instructions'=>'Switch dan 6 PC.','published_at'=>now()->subDays(5),'deadline_at'=>now()->addDays(5),'max_score'=>100,'allow_resubmission'=>true,'status'=>'published']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c2->id,'subject_id'=>$pO->id,'title'=>'Instalasi Linux Ubuntu','description'=>'Ubuntu VirtualBox screenshot.','instructions'=>'Lengkap step by step.','published_at'=>now()->subDays(15),'deadline_at'=>now()->subDays(2),'max_score'=>100,'status'=>'published']);
        Assignment::create(['teacher_id'=>$teacher->id,'class_id'=>$c1->id,'subject_id'=>$pD->id,'title'=>'Query SQL Lanjutan','description'=>'JOIN dan subquery.','instructions'=>'Database sakila.','published_at'=>now()->subDays(3),'deadline_at'=>now()->addDays(10),'max_score'=>100,'allow_late_submission'=>true,'allow_resubmission'=>true,'status'=>'published']);
    }
}
