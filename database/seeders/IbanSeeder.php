<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ibans')->delete();

        $now = now();
        $users = User::where('role', 'user')->take(10)->get();
        $ibans = [];
        $ibanId = 0;

        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $ibanId++;

                $ibans[] = [
                    'id' => $ibanId,
                    'iban' => 'SampleIBAN' . $user->id . $i,
                    'user_id' => $user->id,
                    'created_at' => $now,
                ];
            }
        }

        DB::table('ibans')->insert($ibans);
    }
}
