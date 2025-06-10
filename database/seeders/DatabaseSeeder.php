<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $departments = Department::factory(15)->create();

        Employee::factory(200)->create()->each(function ($employee) use ($departments) {
            $employee->departments()->attach(
                $departments->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}
