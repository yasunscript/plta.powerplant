<?php

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
        DB::table('users')->insert([
            'name' => 'Superadmin',
            'username' => 'su',
            'email' => 'su@plta.id',
            'email_verified_at' => now(),
            'password' => bcrypt('developer'),
            'photo' => 'user1-128x128.jpg',
            'role' => 0
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@plta.id',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'photo' => 'user2-160x160.jpg',
            'role' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@plta.id',
            'email_verified_at' => now(),
            'password' => bcrypt('user'),
            'photo' => 'user6-128x128.jpg',
            'role' => 2
        ]);
    }
}
