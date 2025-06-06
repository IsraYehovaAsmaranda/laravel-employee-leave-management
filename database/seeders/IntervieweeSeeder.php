<?php

namespace Database\Seeders;

use App\Models\Interviewee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IntervieweeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Interviewee::factory(10)->create();
    }
}
