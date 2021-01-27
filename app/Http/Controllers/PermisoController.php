<?php

namespace App\Http\Controllers;

use App\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = Permiso::all();
        return view('permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permisos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado'  => 'required',
            'area' => 'required',
            'fecha_permiso' => 'required',
            'fecha_permiso_final' => 'required',
            'turno' => 'required',
            'sustituto' => 'required',
            'tipo_permiso' => 'required',
        ]);
        Permiso::create([
            'empleado'  => $request->input('empleado'),
            'area' => $request->input('area'),
            'fecha_permiso' => $request->input('fecha_permiso'),
            'fecha_permiso_final' => $request->input('fecha_permiso_final'),
            'turno' => $request->input('turno'),
            'sustituto' => $request->input('sustituto'),
            'tipo_permiso' => $request->input('tipo_permiso'),
            'motivo' => $request->input('motivo'),
        ]);
        $permisos = Permiso::all();

        return redirect()->route('permisos.index')->with(compact('permisos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permisos = Permiso::find($id);
        return view('permisos.edit', compact('permisos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permiso = $request->except('_token');
        Permiso::where('id','=',$id)->update($permiso);
        $permisos = Permiso::all();
        return view('permisos.index',compact('permisos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permiso = Permiso::find($id);
        $permiso->delete();
        return redirect()->back()->with('msg','Permiso eliminado correctamente');
    }
}
