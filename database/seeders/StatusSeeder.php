<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        foreach (StatusEnum::cases() as $status) {
            DB::table('statuses')->updateOrInsert(
                [
                    'name' => $status->value,
                    'is_available' => $status->isAvailable(),
                ]
            );
        }
    }
}
