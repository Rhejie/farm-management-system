<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'firstname' => 'admin',
            'lastname' => 'admin',
            'middlename' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => \bcrypt('123456'),
            'role_id' => 1
        ]);

        User::create([
            'firstname' => 'leadman',
            'lastname' => 'leadman',
            'middlename' => 'leadman',
            'username' => 'leadman',
            'email' => 'leadman@gmail.com',
            'password' => \bcrypt('123456'),
            'role_id' => 2
        ]);

        User::create([
            'firstname' => 'warehouse',
            'lastname' => 'warehouse',
            'middlename' => 'warehouse',
            'username' => 'warehouse',
            'email' => 'warehouse@gmail.com',
            'password' => \bcrypt('123456'),
            'role_id' => 3
        ]);

        User::create([
            'firstname' => 'finance',
            'lastname' => 'finance',
            'middlename' => 'finance',
            'username' => 'finance',
            'email' => 'finance@gmail.com',
            'password' => \bcrypt('123456'),
            'role_id' => 4
        ]);

        User::create([
            'firstname' => 'humanresource',
            'lastname' => 'humanresource',
            'middlename' => 'humanresource',
            'username' => 'humanresource',
            'email' => 'humanresource@gmail.com',
            'password' => \bcrypt('123456'),
            'role_id' => 5
        ]);
    }
}
