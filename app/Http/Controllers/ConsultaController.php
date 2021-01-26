<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consulta;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Role;


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
        $role_admin = Role::where('name', 'admin')->first();  
        $userId = Auth::user()->id;
        $user = User::where('id',$userId)->get();
        $consulta = Consulta::findOrFail($id);
        if($consulta->finalizado == 0){
            $hora = Carbon::now()->timezone('America/Mexico_City')->toTimeString();
            $consulta->hora_atencion = $hora;
            $consulta->save();
            $medicos = User::where('work_area','Hospital')->get();
            return view('consultas.aprove',compact('consulta'))->with(compact('medicos'));
        }else{
            foreach($user[0]->roles as $role){
                if($role->name == $role_admin->name)
                {
                    $medicos = User::where('work_area','Hospital')->get();
                    return view('consultas.aprove',compact('consulta'))->with(compact('medicos'));
                }else{
                    return view('errors.not_authorized_action');
                }
            }
        }
       
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
        switch ($request->input('action')) {
            case 'finalizar':
                $hora = Carbon::now()->timezone('America/Mexico_City')->toTimeString();
                $consulta = Consulta::find($id);
                $consulta->finalizado=1;
                $consulta->hora_termino = $hora;
                $consulta->medico_id = $request->input('medico_id');
                $consulta->propietario = $request->input('propietario');  
                $consulta->mascota = $request->input('mascota'); 
                $consulta->peso = $request->input('peso'); 
                $consulta->edad = $request->input('edad'); 
                $consulta->raza = $request->input('raza'); 
                $consulta->servicio = $request->input('servicio'); 
                $consulta->save();
                //$consulta = $request->except('_token');
                //Consulta::where('id','=',$id)->update($consulta);
                $medicos = User::where('work_area','Hospital')->get();
                $consultas = Consulta::paginate(5);
                return view('consultas.index',compact('consultas'))->with(compact('medicos'));
                break;
            
            case 'guardar':
                $medicos = User::where('work_area','Hospital')->get();
                $consulta = $request->only('medico_id','propietario','raza','edad','peso','mascota','servicio');
                Consulta::where('id','=',$id)->update($consulta);
                $consultas = Consulta::paginate(5);
                return view('consultas.index',compact('consultas'))->with(compact('medicos'));
                break;
            }
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
        $medicos = User::where('work_area','Hospital')->get();
        return redirect()->route('consultas.index');
    }

    public function filterDate(Request $request)
    {
        $fecha_inicial = $request -> input('desde');
        $fecha_final = $request -> input('hasta');
        $selectValue = $request -> input('medico_id');
        switch ($request->input('action')) {
        case 'filtrar':
            if($selectValue != "todos")
            {
                $medico = $request -> input('medico_id');
                $medicos = User::where('work_area','hospital')->get();
                $consultas = Consulta::where('medico_id',$medico)->whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
                return view('consultas.index', [
                    'consultas' => $consultas,
                    'medicos' => $medicos,
                ]);
            }
            else
            {
                $medicos = User::all();
                $consultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
                return view('consultas.index', [
                    'consultas' => $consultas,
                    'medicos' => $medicos,
                ]);
            }
            break;

        case 'imprimir':
            if($selectValue == 'todos')
            {
                $lista = array();
                $medicos = Consulta::distinct('medico_id')->get('medico_id');
                foreach($medicos as $medico){
                    $noConsultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->count('medico_id');
                    $medicosName = User::where('id',$medico->medico_id)->get('name');
                    $maxServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->min('servicio');
                    $minServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->max('servicio');
                    foreach($medicosName as $medicoName){
                        $lista[] = $medicoName->name;
                    }
                    $lista[] = $noConsultas;
                    $lista[] = $maxServices;
                    $lista[] = $minServices;
                    
                }
                $wordTest = new \PhpOffice\PhpWord\PhpWord();
                $newSection = $wordTest->addSection();
                //for($i=o; i<$lista.lenght; i+=4){

                //}
                $part1 = "Nombre del médico" . $lista[0];
                $mytime = Carbon::now()->timezone('America/Mexico_City')->toDateString();
                $fech = "                                                                                     Tuxtla Gutiérrez, Chis. "; 
                $newSection->addText($part1);
                $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
                try{
                    $objectWriter->save(storage_path("Reporte" . $mytime .".docx"));
                }catch(Exception $e){
        
                }
                return response()->download(storage_path("Reporte" . $mytime . ".docx"));
                //dd($lista);
            }else{
                echo 'Hola';

            }
           

            $wordTest = new \PhpOffice\PhpWord\PhpWord();
            $newSection = $wordTest->addSection();
            $mytime = Carbon::now()->timezone('America/Mexico_City')->toDateString();
            $fech = "                                                                                     Tuxtla Gutiérrez, Chis. "; 
            $newSection->addText($fech);
            $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
            try{
                $objectWriter->save(storage_path("Reporte" . $mytime .".docx"));
            }catch(Exception $e){
    
            }
            return response()->download(storage_path("Reporte" . $mytime . ".docx"));
            break;
        }
        
    }
}
