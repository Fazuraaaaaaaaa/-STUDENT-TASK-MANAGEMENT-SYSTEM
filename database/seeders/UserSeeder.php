<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name'=>'Budi Santoso','email'=>'teacher@taskora.test','password'=>Hash::make('password123'),'role'=>'teacher','teacher_number'=>'TCH-001','email_verified_at'=>now()]);
        foreach ([['Rafa Ardian','student1@taskora.test','STU-001'],['Siti Rahayu','student2@taskora.test','STU-002'],['Dimas Putra','student3@taskora.test','STU-003']] as [$name,$email,$number]) User::create(['name'=>$name,'email'=>$email,'password'=>Hash::make('password123'),'role'=>'student','student_number'=>$number,'email_verified_at'=>now()]);
    }
}
