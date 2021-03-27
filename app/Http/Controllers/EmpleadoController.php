<?php

namespace App\Http\Controllers;

use App\empleado;
use App\User;
use App\Role;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = User::paginate(10);
        return view('empleados.index', compact('empleados'));
    }
    public function create()
    {
        return view('empleados.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre_empleado' => 'required|regex:/^[\pL\s\-]+$/u',
            'empleado_username' => 'required|alpha',
            'empleado_area' => 'required',
            'empleado_contraseña' => 'required|alpha_num',
        ]);

        $role = Role::where('name', $request->input('empleado_rol'))->first();   
        $user = new User();
        $user->name = $request->input('nombre_empleado');
        $user->username = $request->input('empleado_username');
        $user->work_area = $request->input('empleado_area');
        $user->address = $request->input('empleado_direccion');
        $user->phone = $request->input('empleado_celular');
        $user->password = bcrypt($request->input('empleado_contraseña'));
        $user->save();
        $user->roles()->attach($role);
        $empleados = User::paginate(10);

        //return view('empleados.index', compact('empleados'));
        return redirect()->route('empleados.index',compact('empleados'));
    }
    public function edit($id)
    {
        $empleado = User::find($id);
        return view('empleados.edit', compact('empleado'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_empleado' => 'required|regex:/^[\pL\s\-]+$/u',
            'empleado_username' => 'required|alpha',
            'empleado_area' => 'required',
            'empleado_contraseña' => 'required|alpha_num',
        ]);
        
        $role = Role::where('name', $request->input('empleado_rol'))->first();
        $empleado = User::find($id);
        $empleado->name = $request->input('empleado_nombre');
        $empleado->username = $request->input('empleado_usuario');
        $empleado->work_area = $request->input('empleado_area');
        $empleado->phone = $request->input('empleado_celular');
        $empleado->address = $request->input('empleado_direccion');
        $empleado->save();
        $empleado->roles()->sync($role);
        $empleados = User::paginate(10);
        return redirect()->route('empleados.index')->with(compact('empleados'));
    }
    public function destroy($id)
    {
        $empleado = User::find($id);
        if($empleado->consultas()->count())
        {
            return redirect()->route('empleados.index')->with('msg','No puedes borrar este empleado, hay una consulta relacionada');
        }
        else if($empleado->permisos()->count()){
            return redirect()->route('empleados.index')->with('msg','No puedes borrar este empleado, hay un permiso relacionada');
        }
        else if($empleado->facturas()->count()){
            return redirect()->route('empleados.index')->with('msg','No puedes borrar este empleado, hay una factura relacionada');
        }
        else{
            $empleado->delete();
            return redirect()->route('empleados.index')->with('msg','Empleado eliminado correctamente');
        }
    }
    
    public function getEmpleados(Request $request)
    {
        if($request->ajax()) {
          
            $data = User::where('name', 'LIKE', $request->propietario . '%')->get();
           
            $output = '';
           
            if (count($data)>0) {
              
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
              
                foreach ($data as $row){
                   
                    $output .= '<li class="list-group-item">'.$row->name.'</li>';
                }
              
                $output .= '</ul>';
            }
            else {
             
                $output .= '<li class="list-group-item">'.'No results'.'</li>';
            }
           
            return $output;
        }
    }
}
