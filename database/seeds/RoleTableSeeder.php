<?php

use Illuminate\Database\Seeder;

use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'admin';
        $role->description = 'Administrador';
        $role->save();        
        
        $role = new Role();
        $role->name = 'cajero';
        $role->description = 'Cajero';
        $role->save();

        $role = new Role();
        $role->name = 'mantenimiento';
        $role->description = 'Encargado de mantenimiento';
        $role->save();

        $role = new Role();
        $role->name = 'medico_consulta';
        $role->description = 'Medico de consulta';
        $role->save();

        $role = new Role();
        $role->name = 'apoyo_medico';
        $role->description = 'Apoyo del area medica';
        $role->save();

        $role = new Role();
        $role->name = 'recepcionista';
        $role->description = 'Encargado del area de recepcion';
        $role->save();

        $role = new Role();
        $role->name = 'estetica';
        $role->description = 'Encargado del area de estetica';
        $role->save();

        $role = new Role();
        $role->name = 'hostess';
        $role->description = 'Encargado de hostess';
        $role->save();
        
        $role = new Role();
        $role->name = 'almacenista';
        $role->description = 'Encargado del area de almacen';
        $role->save();
    }
}
