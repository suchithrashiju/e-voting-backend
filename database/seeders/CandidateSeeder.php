<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Candidate::create([
            'name' => 'Candidate 1',
            'party' => 'Party A',
            'description' => 'Description of Candidate 1',
            'photo_url' => 'candidates/candidate1.jpg',
            'symbol_url' => 'candidates/symbol/s1.jpg',
        ]);
        Candidate::create([
            'name' => 'Candidate 2',
            'party' => 'Party B',
            'description' => 'Description of Candidate 2',
            'photo_url' => 'candidates/candidate2.jpg',
            'symbol_url' => 'candidates/symbol/s2.jpg',
        ]);

        Candidate::create([
            'name' => 'Candidate 3',
            'party' => 'Party C',
            'description' => 'Description of Candidate 3',
            'photo_url' => 'candidates/candidate3.jpg',
            'symbol_url' => 'candidates/symbol/s3.jpg',
        ]);
    }
}
