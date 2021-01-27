<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consulta;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Role;
use DateInterval;
use DateTime;


class ConsultaController extends Controller
{

    public function index()
    {
        $fecha = Carbon::now()->timezone('America/Mexico_City')->toDateString();
        $medicos = User::where('work_area','Hospital')->get();
        $consultas = Consulta::whereDate('fecha', $fecha)->paginate(10);
        return view('consultas.index',compact('consultas'))->with(compact('medicos'));
    }

    public function create()
    {
        $fecha = Carbon::now()->timezone('America/Mexico_City')->toDateString();
        $empleados = User::where('work_area','Hospital')->get();
        return view('consultas.create', compact('empleados'))->with(compact('fecha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'propietario' => 'required',
            'mascota' => 'required',
            'peso' => 'required|numeric',
            'edad' => 'required',
            'raza' => 'required',
            'servicio' => 'required',
        ]);

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
                    return redirect()->route('consultas.aprove',compact('consulta'))->with(compact('medicos'));
                }else{
                    return redirect()->route('errors.not_authorized_action');
                }
            }
        }
       
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'propietario' => 'required',
            'mascota' => 'required',
            'peso' => 'required',
            'edad' => 'required',
            'raza' => 'required',
            'servicio' => 'required',
        ]);

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
                $medicos = User::where('work_area','Hospital')->get();
                $consultas = Consulta::paginate(5);
                return redirect()->route('consultas.index',compact('consultas'))->with(compact('medicos'));
                break;
            
            case 'guardar':
                $medicos = User::where('work_area','Hospital')->get();
                $consulta = $request->only('medico_id','propietario','raza','edad','peso','mascota','servicio');
                Consulta::where('id','=',$id)->update($consulta);
                $consultas = Consulta::paginate(10);
                return redirect()->route('consultas.index',compact('consultas'))->with(compact('medicos'));
                break;
            }
    }

    public function destroy($id)
    {
        $consulta = Consulta::find($id);
        $consulta->delete();
        $medicos = User::where('work_area','Hospital')->get();
        return redirect()->route('consultas.index')->with('msg','Consulta eliminada correctamente');
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
                return view('consultas.index')->with(compact('medicos'))->with(compact('consultas'));
            }
            else
            {
                $medicos = User::all();
                $consultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(10);
                return view('consultas.index')->with(compact('medicos'))->with(compact('consultas'));
            }
            break;

        case 'imprimir':
            if($selectValue == 'todos')
            {
                $fechaT = Carbon::now()->toDateString();
                $fecha = Carbon::parse($fechaT);
                $date = $fecha->locale();

                $lista = array();

                $noConsultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','Consulta')->count();
                $noServicesTotal = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->count();
                $maxServicesTotal = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->min('servicio');
                $minServicesTotal = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->max('servicio');

                $fecha2 = Carbon::parse($fecha_final);
                $fecha1 = Carbon::parse($fecha_inicial);
                $dias = $fecha2->diffindays($fecha1);
                $promedioConsultas = array();
                $promedioServiciosGenerales = array();
                for($i=0; $i<=$dias;$i++)
                {
                    $fechaPivote=new DateTime($fecha_inicial);
                    $intervalo = new DateInterval('P'.$i.'D');
                    $fechaPivote->add($intervalo);
                    $promedioConsultas[] = Consulta::whereDate('fecha',$fechaPivote)->where('servicio','LIKE','%Consulta%')->count();   
                    $promedioServiciosGenerales[] = Consulta::whereDate('fecha',$fechaPivote)->count();
                }
                $contador=0;
                $totalConsultas=0;
                $totalServicios=0;
                for($j=0;$j<count($promedioConsultas);$j++)
                {
                    //if($promedioConsultas[$j]!='0')
                    //{
                        $totalConsultas = $totalConsultas + intval($promedioConsultas[$j]);
                        $totalServicios = $totalServicios + intval($promedioServiciosGenerales[$j]);
                        $contador++;
                    //}
                }
                //$promConPD = $total / $contador;
                $promConPD = $totalConsultas / $contador;
                $promServPD = $totalServicios / $contador;
                $medicos = Consulta::distinct('medico_id')->get('medico_id');
                foreach($medicos as $medico){
                    $noServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->count('medico_id');
                    $noCons = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Consulta%')->count('medico_id');
                    $medicosName = User::where('id',$medico->medico_id)->get('name');
                    $maxServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->min('servicio');
                    $minServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->max('servicio');
                    foreach($medicosName as $medicoName){
                        $lista[] = $medicoName->name;
                    }
                    $lista[] = $noServices;
                    $lista[] = $noCons;
                    $lista[] = $maxServices;
                    $lista[] = $minServices;
                    
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
                $text->setSize(11);

                $parts = array();
                for($i=0; $i<count($lista); $i+=5){
                    $parts[] = "Nombre del médico: " . $lista[$i];
                    $parts[] = "Servicios Generales atendidos: ".$lista[$i+1];
                    $parts[] = "Consultas atendidas: ".$lista[$i+2];
                    $parts[] = "Servicio más solicitado: ".$lista[$i+3];
                    $parts[] = "Servicio menos solicitado: ".$lista[$i+4];
                    $parts[] = "__________________________________________________________________";
                }
                $partHead = "                  Reporte de servicios médicos";
                $partFech = " Periodo: ".$fecha_inicial . " - ".$fecha_final;
                $mytime = Carbon::now()->timezone('America/Mexico_City')->toDateString();
                $fech = "                                                                     Tuxtla Gutiérrez, Chis. ".$fecha->monthName." ".$fecha->day.", ".$fecha->year; 
                $infoG1 = "Total de consultas en este periodo: ".$noConsultas;
                $infoG2 = "Total de servicios en general brindados: ".$noServicesTotal;
                $infoG3 = "Promedio de consultas atendidas por día: ".$promConPD;
                $infoG4 = "Promedio de servicios atendidos por día: ".$promServPD;
                $infoG5 = "Servicio más solicitado: ".$maxServicesTotal;
                $infoG6 = "Servicio menos solicitado: ".$minServicesTotal;
                $saltoline = "____________________________________________________________________";

                $newSection->addText($fech, $subtitule);
                $newSection->addText($partHead, $fontStyle);
                $newSection->addText($partFech, $subtitule);
                $newSection->addText($infoG1,$text);
                $newSection->addText($infoG2,$text);
                $newSection->addText($infoG3,$text);
                $newSection->addText($infoG4,$text);
                $newSection->addText($infoG5,$text);
                $newSection->addText($infoG6,$text);
                $newSection->addText($saltoline,$text);
                for($i=0; $i<count($parts); $i++){
                    $newSection->addText($parts[$i], $text);
                }

                $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
                try{
                    $objectWriter->save(storage_path("Reporte" . $mytime .".docx"));
                }catch(Exception $e){
        
                }
                return response()->download(storage_path("Reporte" . $mytime . ".docx"));
                //dd($lista);
            }else{
                $fechaT = Carbon::now()->toDateString();
                $fecha = Carbon::parse($fechaT);
                $date = $fecha->locale();

                $noConsultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Consulta%')->count('medico_id');
                $medicosName = User::where('id',$request->input('medico_id'))->get('name');
                foreach($medicosName as $medicoName){
                    $MedicoNombre = $medicoName->name;
                }
                $maxServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->min('servicio');
                $minServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->max('servicio');
                $noServGenerales = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->count('medico_id');

                $fecha2 = Carbon::parse($fecha_final);
                $fecha1 = Carbon::parse($fecha_inicial);
                $dias = $fecha2->diffindays($fecha1);
                $promedioConsultas = array();
                $promedioServiciosGenerales = array();
                for($i=0; $i<=$dias;$i++)
                {
                    $fechaPivote=new DateTime($fecha_inicial);
                    $intervalo = new DateInterval('P'.$i.'D');
                    $fechaPivote->add($intervalo);
                    $promedioConsultas[] = Consulta::whereDate('fecha',$fechaPivote)->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Consulta%')->count('medico_id');   
                    $promedioServiciosGenerales[] = Consulta::whereDate('fecha',$fechaPivote)->where('medico_id',$request->input('medico_id'))->count('medico_id');
                }
                $contador=0;
                $totalConsultas=0;
                $totalServicios=0;
                for($j=0;$j<count($promedioConsultas);$j++)
                {
                    //if($promedioConsultas[$j]!='0')
                    //{
                        $totalConsultas = $totalConsultas + intval($promedioConsultas[$j]);
                        $totalServicios = $totalServicios + intval($promedioServiciosGenerales[$j]);
                        $contador++;
                    //}
                }
                //$promConPD = $total / $contador;
                $promConPD = $totalConsultas / $contador;
                $promServPD = $totalServicios / $contador;

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

                $partHead = "                  Reporte de servicios médicos";
                $partFech = " Periodo: ".$fecha_inicial . " - ".$fecha_final;
                $mytime = Carbon::now()->timezone('America/Mexico_City')->toDateString();
                $fech = "                                                                     Tuxtla Gutiérrez, Chis. ".$fecha->monthName." ".$fecha->day.", ".$fecha->year; 
                $saltoline = "__________________________________________________________________";
                $infoG1 = "Medico: ".$MedicoNombre;
                $infoG2 = "No. de Consultas Atendidas: ".$noConsultas;
                $infoG3 = "No. de Servicios Generales: ".$noServGenerales;
                $infoG4 = "Máximo servicio solicitado: ".$maxServices;
                $infoG5 = "Mínimo servicio solicitado: ".$minServices;
                $infoG6 = "Promedio de Consultas por día: ".$promConPD;
                $infoG7 = "Promedio de Servicios atendidos por día: ".$promServPD;
                $newSection->addText($fech, $subtitule);
                $newSection->addText($partHead, $fontStyle);
                $newSection->addText($partFech, $subtitule);
                $newSection->addText($saltoline,$text);
                $newSection->addText($infoG1,$subtitule);
                $newSection->addText($infoG2,$text);
                $newSection->addText($infoG3,$text);
                $newSection->addText($infoG4,$text);
                $newSection->addText($infoG5,$text);
                $newSection->addText($infoG6,$text);
                $newSection->addText($infoG7,$text);
                $newSection->addText($saltoline,$text);
                $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
                try{
                    $objectWriter->save(storage_path("Reporte" . $mytime .".docx"));
                }catch(Exception $e){
        
                }
                return response()->download(storage_path("Reporte" . $mytime . ".docx"));
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
