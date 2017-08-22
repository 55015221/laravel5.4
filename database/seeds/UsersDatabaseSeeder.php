<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::table('users')->delete();

        $users = [
            ['username' => 'admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('admin')],
            ['username' => 'demo', 'email' => 'demo@scotch.io', 'password' => Hash::make('demo')],
        ];

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user) {
            User::create($user);
        }
        Model::reguard();
    }
}