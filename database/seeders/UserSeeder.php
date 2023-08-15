<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $now = now();
        $admins = [];
        $users = [];
        $userId = 0;
        $password = bcrypt('password');

        for ($i = 1; $i <= 3; $i++) {
            $userId++;

            $admins[] = [
                'id' => $userId,
                'name' => 'Admin User' . $i,
                'email' => "admin{$i}@example.com",
                'password' => $password,
                'role' => 'admin',
                'created_at' => $now,
            ];
        }

        for ($i = 1; $i <= 5; $i++) {
            $userId++;

            $users[] = [
                'id' => $userId,
                'name' => 'Regular User' . $i,
                'email' => "user{$i}@example.com",
                'password' => $password,
                'role' => 'user',
                'created_at' => $now,
            ];
        }

        $allUsers = array_merge($admins, $users);

        DB::table('users')->insert($allUsers);
    }
}
