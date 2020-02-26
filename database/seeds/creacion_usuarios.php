<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class creacion_usuarios extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([ // create a new user
            'email' => 'bersain',
            'password' => Hash::make('bersain2020'),
            'username' => 'Bersain',
            'nombre' => 'Bersain',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Bersa',
            'is_superuser' => 0
        ]);

        User::create([ // create a new user
            'email' => 'salvador',
            'password' => Hash::make('salvador2020'),
            'username' => 'Salvador',
            'nombre' => 'Salvador',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Chava',
            'is_superuser' => 0
        ]);

        User::create([ // create a new user
            'email' => 'panchito',
            'password' => Hash::make('panchito2020'),
            'username' => 'Panchito',
            'nombre' => 'Panchito',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Panchito',
            'is_superuser' => 0
        ]);
    }
}
