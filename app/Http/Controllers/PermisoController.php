<?php

namespace App\Http\Controllers;

use App\Permiso;
use App\User;
use Illuminate\Http\Request;
use DateInterval;
use DateTime;
use Carbon\Carbon;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $permisos = Permiso::whereDate('fecha_permiso', today())->paginate(10);
        $empleados = User::all();
        return view('permisos.index', compact('permisos'))->with(compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empleados = User::all();
        return view('permisos.create',compact('empleados'));
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
        $empleados = User::all();
        $permiso = Permiso::find($id);
        return view('permisos.edit', compact('permiso'))->with(compact('empleados'));
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
    public function filterDate(Request $request)
    {
        $fecha_inicial = $request -> input('desde');
        $fecha_final = $request -> input('hasta');
        $selectValue = $request -> input('empleado_id');
        switch ($request->input('action')) {
        case 'filtrar':
            if($selectValue != "todos")
            {
                $empleado = $request -> input('empleado_id');
                $empleados = User::all();
                $permisos = Permiso::where('empleado',$empleado)->whereBetween('fecha_permiso',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
                return view('permisos.index', [
                    'permisos' => $permisos,
                    'empleados' => $empleados,
                ]);
            }
            else
            {
                $empleados = User::all();
                $permisos = Permiso::whereBetween('fecha_permiso',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
                return view('permisos.index',compact('empleados'))->with(compact('permisos'));
            }
            break;

        case 'imprimir':
                $fechaT = Carbon::now()->toDateString();
                $fecha = Carbon::parse($fechaT);
                $date = $fecha->locale();

                $noPermisosTotal = Permiso::whereBetween('fecha_permiso',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->count();
                $empleadosName = Permiso::whereBetween('fecha_permiso',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->get();
                $empleados = Permiso::distinct('empleado')->get('empleado');
                $EmpleadoNombre = array();
                $EmpleadoCount = array();
                $PermPorEmp = array();
                foreach($empleados as $empleado){
                    $EmpleadoCount[] = Permiso::whereBetween('fecha_permiso',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('empleado',$empleado->empleado)->count();
                    $empleadosName = User::where('id',$empleado->empleado)->get('name');
                    foreach($empleadosName as $empleadoName){
                        $EmpleadoNombre[] = $empleadoName->name;
                    }
                }
                //dd($PermPorEmp, $EmpleadoNombre);
                for($i=0;$i<count($empleados);$i++)
                {
                    $PermPorEmp[] = "Empleado: ".$EmpleadoNombre[$i]." Solicitó ".$EmpleadoCount[$i]." permiso(s)";    
                }
               
                $wordTest = new \PhpOffice\PhpWord\PhpWord();
                $newSection = $wordTest->addSection();

                $fontStyle = new \PhpOffice\PhpWord\Style\Font();
                $fontStyle->setBold(true);
                $fontStyle->setName('Arial');
                $fontStyle->setSize(22);
        
                $subtitule = new \PhpOffice\PhpWord\Style\Font();
                $subtitule->setBold(true);
                $subtitule->setName('Arial');
                $subtitule->setSize(12);
        
                $text =  new \PhpOffice\PhpWord\Style\Font();
                $text->setName('Arial');
                $text->setSize(12);

                $partHead = "                  Reporte de permisos";
                $partFech = " Periodo: ".$fecha_inicial . " - ".$fecha_final;
                $mytime = Carbon::now()->timezone('America/Mexico_City')->toDateString();
                $fech = "                                                                     Tuxtla Gutiérrez, Chis. ".$fecha->monthName." ".$fecha->day.", ".$fecha->year; 
                $saltoline = "__________________________________________________________________";
                $infoG1 = "No. de Permisos Solicitados: ".$noPermisosTotal;
                
                $newSection->addText($fech, $subtitule);
                $newSection->addText($partHead, $fontStyle);
                $newSection->addText($partFech, $subtitule);
                $newSection->addText($saltoline,$text);
                $newSection->addText($infoG1,$subtitule);
                for($i=0;$i<count($empleados);$i++)
                {   
                    $newSection->addText($PermPorEmp[$i],$text);
                }
                $newSection->addText($saltoline,$text);
                $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
                try{
                    $objectWriter->save(storage_path("ReportePermisos" . $mytime .".docx"));
                }catch(Exception $e){
        
                }
                return response()->download(storage_path("ReportePermisos" . $mytime . ".docx"));
            }
    }
}
