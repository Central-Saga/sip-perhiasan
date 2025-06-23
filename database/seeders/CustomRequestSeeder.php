<?php

namespace Database\Seeders;

use App\Models\CustomRequest;
use Illuminate\Database\Seeder;

class CustomRequestSeeder extends Seeder
{
    public function run(): void
    {
        CustomRequest::factory()
            ->count(15)
            ->create();
    }
}
