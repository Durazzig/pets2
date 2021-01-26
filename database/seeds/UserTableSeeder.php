<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Role;

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
