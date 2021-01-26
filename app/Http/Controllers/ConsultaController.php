<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consulta;
use Carbon\Carbon;
use App\User;
use App\empleado;

class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicos = User::where('work_area','Hospital')->get();
        $consultas = Consulta::whereDate('fecha', today())->paginate(10);
        return view('consultas.index',compact('consultas'))->with(compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha = Carbon::now()->toDateString();
        $empleados = User::where('work_area','Hospital')->get();
        return view('consultas.create', compact('empleados'))->with(compact('fecha'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        /*$request->validate([
            'propietario' => 'required',
            'mascota' => 'required',
            'peso' => 'required',
            'edad' => 'required',
            'raza' => 'required',
            'servicio' => 'required',
        ]);*/

        $hora = Carbon::now()->timezone('America/Mexico_City')->toTimeString();
        Consulta::create([
            'fecha' => $request->input('fecha'),
            'medico_id'  => $request->input('medico_id'),
            'hora_llegada' => $hora,
            'hora_atencion' => $request->input('hora_atencion'),
            'hora_termino' => $request->input('hora_termino'),
            'propietario' => $request->input('propietario'),
            'mascota' => $request->input('mascota'),
            'peso' => $request->input('peso'),
            'edad' => $request->input('edad'),
            'raza' => $request->input('raza'),
            'servicio' => $request->input('servicio'),
        ]);

        return redirect()->route('consultas.index');
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
        $consulta = Consulta::findOrFail($id);
        if($consulta->aprobado == 'nochecked'){
            $hora = Carbon::now()->timezone('America/Mexico_City')->toTimeString();
            $consulta->hora_atencion = $hora;
            $consulta->save();
        }
        $medicos = User::where('work_area','Hospital')->get();
        return view('consultas.aprove',compact('consulta'))->with(compact('medicos'));
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
        //dd($request);
        $consulta = $request->except('_token');
        Consulta::where('id','=',$id)->update($consulta);
        $consultas = Consulta::paginate(5);
        return view('consultas.index',compact('consultas'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $consulta = Consulta::find($id);
        $consulta->delete();
        $medicos = empleado::where('area','Hospital')->get();
        return redirect()->route('consultas.index');
    }

    public function filterDate(Request $request)
    {
        $fecha_inicial = $request -> input('desde');
        $fecha_final = $request -> input('hasta');
        $selectValue = $request -> input('medico_id');
        if($selectValue != "todos")
        {
            $medico = $request -> input('medico_id');
            $medicos = empleado::where('area','hospital')->get();
            $consultas = Consulta::where('medico_id',$medico)->whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
            return view('consultas.index', [
                'consultas' => $consultas,
                'medicos' => $medicos,
            ]);
        }
        else
        {
            $medicos = empleado::all();
            $consultas = Bill::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
            return view('consultas.index', [
                'consultas' => $consultas,
                'medicos' => $medicos,
            ]);
        }
        
    }
}
