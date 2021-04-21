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

        User::create([ // create a new user
            'email' => 'rubi',
            'password' => Hash::make('rubi2020'),
            'username' => 'rubi',
            'nombre' => 'Rubi',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Rubi',
            'is_superuser' => 0,
            'created_at' => '2020-03-27 17:31:51.565',
            'updated_at' => '2020-03-27 17:31:51.565'
        ]);
        User::create([ // create a new user
            'email' => 'berrio',
            'password' => Hash::make('b12kamas_2020'),
            'username' => 'berrio',
            'nombre' => 'H.B.C. Berriozabal 12 camas',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Rubi',
            'is_superuser' => 0,
            'created_at' => '2020-05-25 17:31:51.565',
            'updated_at' => '2020-05-25 17:31:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'saul',
            'password' => Hash::make('saul_2020'),
            'username' => 'saul',
            'nombre' => 'Saul Vazquez Nucamendi',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Saul',
            'is_superuser' => 0,
            'created_at' => '2020-08-31 13:46:51.565',
            'updated_at' => '2020-08-31 13:46:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'ofcentral',
            'password' => Hash::make('oficentral*2020'),
            'username' => 'ofcentral',
            'nombre' => 'Oficina Central',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'of central',
            'is_superuser' => 0,
            'created_at' => '2020-08-31 13:46:51.565',
            'updated_at' => '2020-08-31 13:46:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'edgarzarate',
            'password' => Hash::make('edgarzarate*21'),
            'username' => 'edgarzarate',
            'nombre' => 'Edgar Zarate Maza',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'Edgar Zarate',
            'is_superuser' => 0,
            'created_at' => '2021-01-08 09:16:51.565',
            'updated_at' => '2021-01-08 09:16:51.565'
        ]);


        User::create([ // create a new user
            'email' => 'sagustin',
            'password' => Hash::make('sagustin2021*'),
            'username' => 'sagustin',
            'nombre' => 'San Agustin',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'alias' => 'San Agustin',
            'is_superuser' => 0,
            'created_at' => '2021-01-25 09:44:51.565',
            'updated_at' => '2021-01-25 09:44:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'ricardo',
            'password' => Hash::make('vegarica21*'),
            'username' => 'vegaricardo',
            'nombre' => 'Ricardo Idelfonso',
            'apellido_paterno' => 'Vega',
            'apellido_materno' => 'salazar',
            'alias' => 'rivs',
            'is_superuser' => 0,
            'created_at' => '2021-02-17 17:54:56.565',
            'updated_at' => '2021-02-17 17:56:51.565'
        ]);
        */

        User::create([ // create a new user
            'email' => 'bancosangre',
            'password' => Hash::make('bs2021*'),
            'username' => 'bancosangre',
            'nombre' => 'Banco de Sangre',
            'apellido_paterno' => '-',
            'apellido_materno' => '-',
            'alias' => 'bs',
            'is_superuser' => 0,
            'created_at' => '2021-04-15 13:00:56.565',
            'updated_at' => '2021-04-15 13:00:51.565'
        ]);

        User::create([ // create a new user
            'email' => 'gomezmaza',
            'password' => Hash::make('gm2021*'),
            'username' => 'gomezmaza',
            'nombre' => 'Gomez Maza',
            'apellido_paterno' => '-',
            'apellido_materno' => '-',
            'alias' => 'gm',
            'is_superuser' => 0,
            'created_at' => '2021-04-15 13:00:56.565',
            'updated_at' => '2021-04-15 13:00:51.565'
        ]);
    }
}
