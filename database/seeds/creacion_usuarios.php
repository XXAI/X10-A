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
        /*User::create([ // create a new user
            'email' => 'bersain',
            'password' => Hash::make('bersain2020'),
            'username' => 'Bersain',
            'nombre' => 'Bersain',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Bersa',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);
        User::create([ // create a new user
            'email' => 'salvador',
            'password' => Hash::make('salvador2020'),
            'username' => 'Salvador',
            'nombre' => 'Salvador',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'GOV',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'panchito',
            'password' => Hash::make('panchito2020'),
            'username' => 'Panchito',
            'nombre' => 'Panchito',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Panchito',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'rosa',
            'password' => Hash::make('rosacamacho'),
            'username' => 'rosa',
            'nombre' => 'rosa',
            'apellido_paterno' => 'auralia',
            'apellido_materno' => 'camacho',
            'alias' => 'CAR',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'pio',
            'password' => Hash::make('pioperez'),
            'username' => 'pio',
            'nombre' => 'pio oswaldo',
            'apellido_paterno' => 'perez',
            'apellido_materno' => 'velasquez',
            'alias' => 'PEV',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'contrato',
            'password' => Hash::make('contrato2020'),
            'username' => 'contrato',
            'nombre' => 'contrato',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'CON',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);
        
    }
}
