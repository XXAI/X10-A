<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //Add this lines
        User::query()->truncate(); // truncate user table each time of seeders run
        User::create([ // create a new user
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'username' => 'Administrator',
            'nombre' => 'Administrator',
            'apellido_paterno' => 'Administrator',
            'apellido_materno' => 'Administrator',
            'alias' => 'Admin',
            'is_superuser' => 1
        ]);
    }
}
