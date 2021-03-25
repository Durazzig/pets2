<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Role;
use Carbon\Carbon;
use App\Consulta;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_cajero = Role::where('name', 'cajero')->first();
        $role_admin = Role::where('name', 'admin')->first();   
        
        for ($i=0; $i < 20000; $i++) {
            $userData[] = [
                'fecha' => Carbon::now()->timezone('America/Mexico_City')->toDateString(),
                'medico_id' => 3,
                'propietario' => 'Tadeo Durazo',
                'mascota' => 'Zeus',
                'peso' => '5',
                'edad' => '13',
                'raza' => 'Chihuahua',
                'servicio' => 'Consulta',
                'finalizado' => '1',
            ];
        }

        for ($i=0; $i < 20000; $i++) {
            $userData[] = [
                'fecha' => Carbon::now()->timezone('America/Mexico_City')->toDateString(),
                'medico_id' => 4,
                'propietario' => 'Tadeo Durazo',
                'mascota' => 'Zeus',
                'peso' => '5',
                'edad' => '13',
                'raza' => 'Chihuahua',
                'servicio' => 'Consulta',
                'finalizado' => '1',
            ];
        }

        for ($i=0; $i < 20000; $i++) {
            $userData[] = [
                'fecha' => Carbon::now()->timezone('America/Mexico_City')->toDateString(),
                'medico_id' => 5,
                'propietario' => 'Tadeo Durazo',
                'mascota' => 'Zeus',
                'peso' => '5',
                'edad' => '13',
                'raza' => 'Chihuahua',
                'servicio' => 'Consulta',
                'finalizado' => '1',
            ];
        }

        for ($i=0; $i < 20000; $i++) {
            $userData[] = [
                'fecha' => Carbon::now()->timezone('America/Mexico_City')->toDateString(),
                'medico_id' => 6,
                'propietario' => 'Tadeo Durazo',
                'mascota' => 'Zeus',
                'peso' => '5',
                'edad' => '13',
                'raza' => 'Chihuahua',
                'servicio' => 'Consulta',
                'finalizado' => '1',
            ];
        }

        for ($i=0; $i < 20000; $i++) {
            $userData[] = [
                'fecha' => Carbon::now()->timezone('America/Mexico_City')->toDateString(),
                'medico_id' => 7,
                'propietario' => 'Tadeo Durazo',
                'mascota' => 'Zeus',
                'peso' => '5',
                'edad' => '13',
                'raza' => 'Chihuahua',
                'servicio' => 'Consulta',
                'finalizado' => '1',
            ];
        }

        foreach ($userData as $user) {
            Consulta::create($user);
        }     
        
        $user = new User();
        $user->name = 'Administrador';
        $user->username = 'admin';
        $user->password = bcrypt('admin');
        $user->save();
        $user->roles()->attach($role_admin);        
        
        $user = new User();
        $user->name = 'Cajero';
        $user->username = 'cajero';
        $user->password = bcrypt('cajero');
        $user->save();
        $user->roles()->attach($role_cajero);
    }
}
