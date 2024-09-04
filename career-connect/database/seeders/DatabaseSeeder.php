<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Company;
use App\Models\Opening;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('applications')->truncate();
        DB::table('openings')->truncate();
        DB::table('students')->truncate();
        DB::table('companies')->truncate();
        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $users = User::factory(10)->create();
        
        $students = $users->take(7)->map(function ($user) {
            return Student::factory()->create(['user_id' => $user->id]);
        });
        
        $companies = $users->skip(7)->take(2)->map(function ($user) {
            return Company::factory()->create(['user_id' => $user->id]);
        });
       
        $adminUser = $users->last();
        Admin::factory()->create(['user_id' => $adminUser->id]);
        
        $openings = $companies->flatMap(function ($company) {
            return Opening::factory(3)->create(['company_id' =>
            $company->id]);
        });
        
        $students->each(function ($student) use ($openings) {
            $randomOpenings = $openings->random(rand(1, 3));
            foreach ($randomOpenings as $opening) {
                Application::factory()->create([
                    'opening_id' => $opening->id,
                    'student_id' => $student->id,
                ]);
            }
        });
    }
}
