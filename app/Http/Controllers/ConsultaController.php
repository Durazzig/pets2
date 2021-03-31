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
        $consultas = Consulta::whereDate('fecha', $fecha)->paginate(5);
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
            'propietario' => 'required|regex:/^[\pL\s\-]+$/u',
            'mascota' => 'required',
            'peso' => 'required|numeric',
            'edad' => 'required|numeric',
            'raza' => 'required|alpha',
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
    public function citar()
    {
        $fecha = Carbon::now()->timezone('America/Mexico_City')->toDateString();
        $empleados = User::where('work_area','Hospital')->get();
        return view('consultas.cita', compact('empleados'))->with(compact('fecha'));
    }

    public function storecita(Request $request)
    {
        $request->validate([
            'fecha' => 'required',
            'propietario' => 'required',
            'hora_llegada' => 'required',
            'mascota' => 'required',
            'edad' => 'required',
            'raza' => 'required',
            'servicio' => 'required',
        ]);
        $existe = Consulta::where('medico_id',$request->input('medico_id'))->where('fecha',$request->input('fecha'))->exists();
        if($existe){
            return redirect()->route('consultas.citar')->with('msg','Elija una hora o fecha diferente');
        }else{
            Consulta::create([
                'fecha' => $request->input('fecha'),
                'medico_id'  => $request->input('medico_id'),
                'hora_llegada' => $request->input('hora_llegada'),
                'propietario' => $request->input('propietario'),
                'mascota' => $request->input('mascota'),
                'edad' => $request->input('edad'),
                'raza' => $request->input('raza'),
                'servicio' => $request->input('servicio'),
            ]);
    
            return redirect()->route('consultas.index');
        }
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
                    return view('consultas.aprove',compact('consulta'))->with(compact('medicos'));
                }else{
                    return view('errors.not_authorized_action');
                }
            }
        }
       
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'propietario' => 'required|regex:/^[\pL\s\-]+$/u',
            'mascota' => 'required',
            'peso' => 'required|numeric',
            'edad' => 'required|numeric',
            'raza' => 'required|alpha',
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
                $consultas = Consulta::where('medico_id',$medico)->whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(5);
                $consultas->appends($request->all());
                return view('consultas.index')->with(compact('medicos'))->with(compact('consultas'));
            }
            else
            {
                $medicos = User::all();
                $consultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->paginate(5);
                $consultas->appends($request->all());
                return view('consultas.index')->with(compact('medicos'))->with(compact('consultas'));
            }
            break;

        case 'imprimir':
            $role_admin = Role::where('name', 'admin')->first();  
            $userId = Auth::user()->id;
            $user = User::where('id',$userId)->get();
            foreach($user[0]->roles as $role){
                if($role->name == $role_admin->name)
                {}else{
                    return view('errors.not_authorized_action');
                }
            }

            if($selectValue == 'todos')
            {
                $fechaT = Carbon::now()->toDateString();
                $fecha = Carbon::parse($fechaT);
                $date = $fecha->locale();
                
                $noConsultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Consulta%')->where('finalizado','1')->count();
                $noUrgencias = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Urgencia%')->where('finalizado','1')->count();
                $noRevision = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Revisión%')->where('finalizado','1')->count();
                $noPlaca = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Placa%')->where('finalizado','1')->count();
                $noDespa = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Desparasitacion%')->where('finalizado','1')->count();
                $noEutana = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Eutanasia%')->where('finalizado','1')->count();
                $noLab = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('servicio','LIKE','%Lab%')->where('finalizado','1')->count();
                
                
                $listaAComparar = array();
                $listaAComparar[0] = $noConsultas;
                $listaAComparar[1] = $noUrgencias;
                $listaAComparar[2] = $noRevision;
                $listaAComparar[3] = $noPlaca;
                $listaAComparar[4] = $noDespa;
                $listaAComparar[5] = $noEutana;
                $listaAComparar[6] = $noLab;
                $mayor = 0;
                $menor = 0;
                $listaIndices = $listaAComparar;
                for($i=1; $i<count($listaAComparar);$i++){
                    for($j=0; $j<count($listaAComparar)-$i;$j++){
                        if($listaAComparar[$j]>$listaAComparar[$j+1])
                        {
                            $k=$listaAComparar[$j+1];
                            $listaAComparar[$j+1]=$listaAComparar[$j];
                            $listaAComparar[$j]=$k;
                        }
                    }
                }
                $CeroEncontrado = 0;
                for($i=0; $i<count($listaAComparar);$i++){
                    if($listaAComparar[$i]==0){
                        $CeroEncontrado = $i;
                    }
                }
                if($CeroEncontrado==5){
                    for($i=0;$i<count($listaAComparar);$i++)
                    {
                        if($listaIndices[$i]==$listaAComparar[6]){
                            $mayor=$i;
                        }elseif($listaIndices[$i]==$listaAComparar[0])
                        {
                            $menor=$i;
                        }
                    }
                }elseif($CeroEncontrado==0){
                    for($i=0;$i<count($listaAComparar);$i++)
                    {
                        if($listaIndices[$i]==$listaAComparar[6]){
                            $mayor=$i;
                        }elseif($listaIndices[$i]==$listaAComparar[$CeroEncontrado])
                        {
                            $menor=$i;
                        }
                    }
                }
                else{
                    for($i=0;$i<count($listaAComparar);$i++)
                    {
                        if($listaIndices[$i]==$listaAComparar[6]){
                            $mayor=$i;
                        }elseif($listaIndices[$i]==$listaAComparar[$CeroEncontrado+1])
                        {
                            $menor=$i;
                        }
                    }
                }
                //dd($CeroEncontrado,$listaAComparar);
                
                $listaServices = array();
                $listaServices[0] = "Consulta";
                $listaServices[1] = "Urgencia";
                $listaServices[2] = "Revision";
                $listaServices[3] = "Placa";
                $listaServices[4] = "Desparasitación";
                $listaServices[5] = "Eutanasia";
                $listaServices[6] = "Laboratorio";
                $noServicesTotal = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('finalizado','1')->count();
                //$maxServicesTotal = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->min('servicio');
                //$minServicesTotal = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->max('servicio');

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
                    $promedioConsultas[] = Consulta::whereDate('fecha',$fechaPivote)->where('servicio','LIKE','%Consulta%')->where('finalizado','1')->count();   
                    $promedioServiciosGenerales[] = Consulta::whereDate('fecha',$fechaPivote)->where('finalizado','1')->count();
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

                    $noServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('finalizado','1')->count('medico_id');
                    
                    $noConsPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Consulta%')->where('finalizado','1')->count('medico_id');
                    $noUrgenciasPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Urgencia%')->where('finalizado','1')->count();
                    $noRevisionPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Revisión%')->where('finalizado','1')->count();
                    $noPlacaPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Placa%')->where('finalizado','1')->count();
                    $noDespaPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Desparasitacion%')->where('finalizado','1')->count();
                    $noEutanaPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Eutanasia%')->where('finalizado','1')->count();
                    $noLabPM = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->where('servicio','LIKE','%Lab%')->where('finalizado','1')->count();
                    
                    $listaACompararPM = array();
                    $listaACompararPM[0] = $noConsPM;
                    $listaACompararPM[1] = $noUrgenciasPM;
                    $listaACompararPM[2] = $noRevisionPM;
                    $listaACompararPM[3] = $noPlacaPM;
                    $listaACompararPM[4] = $noDespaPM;
                    $listaACompararPM[5] = $noEutanaPM;
                    $listaACompararPM[6] = $noLabPM;
                    $mayorPM = 0;
                    $menorPM = 0;
                    $listaIndicesPM = $listaACompararPM;
                    for($i=1; $i<count($listaACompararPM);$i++){
                        for($j=0; $j<count($listaACompararPM)-$i;$j++){
                            if($listaACompararPM[$j]>$listaACompararPM[$j+1])
                            {
                                $k=$listaACompararPM[$j+1];
                                $listaACompararPM[$j+1]=$listaACompararPM[$j];
                                $listaACompararPM[$j]=$k;
                            }
                        }
                    }
                    $CeroEncontrado = 0;
                    for($i=0; $i<count($listaACompararPM);$i++){
                        if($listaACompararPM[$i]==0){
                            $CeroEncontrado = $i;
                        }
                    }
                    if($CeroEncontrado==5){
                        for($i=0;$i<count($listaACompararPM);$i++)
                        {
                            if($listaIndicesPM[$i]==$listaACompararPM[6]){
                                $mayorPM=$i;
                            }elseif($listaIndicesPM[$i]==$listaACompararPM[0])
                            {
                                $menorPM=$i;
                            }
                        }
                    }elseif($CeroEncontrado==0){
                        for($i=0;$i<count($listaACompararPM);$i++)
                        {
                            if($listaIndicesPM[$i]==$listaACompararPM[6]){
                                $mayorPM=$i;
                            }elseif($listaIndicesPM[$i]==$listaACompararPM[$CeroEncontrado])
                            {
                                $menorPM=$i;
                            }
                        }
                    }
                    else{
                        for($i=0;$i<count($listaACompararPM);$i++)
                        {
                            if($listaIndicesPM[$i]==$listaACompararPM[6]){
                                $mayorPM=$i;
                            }elseif($listaIndicesPM[$i]==$listaACompararPM[$CeroEncontrado+1])
                            {
                                $menorPM=$i;
                            }
                        }
                    }
                    
                    $listaServicesPM = array();
                    $listaServicesPM[0] = "Consulta";
                    $listaServicesPM[1] = "Urgencia";
                    $listaServicesPM[2] = "Revision";
                    $listaServicesPM[3] = "Placa";
                    $listaServicesPM[4] = "Desparasitación";
                    $listaServicesPM[5] = "Eutanasia";
                    $listaServicesPM[6] = "Laboratorio";

                    $medicosName = User::where('id',$medico->medico_id)->get('name');
                    //$maxServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->min('servicio');
                    //$minServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$medico->medico_id)->max('servicio');
                    foreach($medicosName as $medicoName){
                        $lista[] = $medicoName->name;
                    }
                    $lista[] = $noServices;
                    $lista[] = $noConsPM;
                    $lista[] = $listaServicesPM[$mayorPM];
                    $lista[] = $listaServicesPM[$menorPM];
                    
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
                //dd($lista);
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
                $infoG3 = "Promedio de consultas atendidas por día: ".round($promConPD);
                $infoG4 = "Promedio de servicios atendidos por día: ".round($promServPD);
                $infoG5 = "Servicio más solicitado: ".$listaServices[$mayor];
                $infoG6 = "Servicio menos solicitado: ".$listaServices[$menor];
                $saltoline = "____________________________________________________________________";

                //$newSection->addImage('https://i.postimg.cc/JnkxkFTG/logopets-1.jpg');
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

                //$noConsultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Consulta%')->count('medico_id');
                $medicosName = User::where('id',$request->input('medico_id'))->get('name');
                foreach($medicosName as $medicoName){
                    $MedicoNombre = $medicoName->name;
                }
                $noConsultas = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Consulta%')->where('finalizado','1')->count();
                $noUrgencias = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Urgencia%')->where('finalizado','1')->count();
                $noRevision = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Revisión%')->where('finalizado','1')->count();
                $noPlaca = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Placa%')->where('finalizado','1')->count();
                $noDespa = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Desparasitazión%')->where('finalizado','1')->count();
                $noEutana = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Eutanasia%')->where('finalizado','1')->count();
                $noLab = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Lab%')->where('finalizado','1')->count();

                $listaAComparar = array();
                $listaAComparar[0] = $noConsultas;
                $listaAComparar[1] = $noUrgencias;
                $listaAComparar[2] = $noRevision;
                $listaAComparar[3] = $noPlaca;
                $listaAComparar[4] = $noDespa;
                $listaAComparar[5] = $noEutana;
                $listaAComparar[6] = $noLab;
                $mayor = 0;
                $menor = 0;
                $listaIndices = $listaAComparar;
                for($i=1; $i<count($listaAComparar);$i++){
                    for($j=0; $j<count($listaAComparar)-$i;$j++){
                        if($listaAComparar[$j]>$listaAComparar[$j+1])
                        {
                            $k=$listaAComparar[$j+1];
                            $listaAComparar[$j+1]=$listaAComparar[$j];
                            $listaAComparar[$j]=$k;
                        }
                    }
                }
                $CeroEncontrado = 0;
                for($i=0; $i<count($listaAComparar);$i++){
                    if($listaAComparar[$i]==0){
                        $CeroEncontrado = $i;
                    }
                }
                if($CeroEncontrado==5){
                    for($i=0;$i<count($listaAComparar);$i++)
                    {
                        if($listaIndices[$i]==$listaAComparar[6]){
                            $mayor=$i;
                        }elseif($listaIndices[$i]==$listaAComparar[0])
                        {
                            $menor=$i;
                        }
                    }
                }elseif($CeroEncontrado==0){
                    for($i=0;$i<count($listaAComparar);$i++)
                    {
                        if($listaIndices[$i]==$listaAComparar[6]){
                            $mayor=$i;
                        }elseif($listaIndices[$i]==$listaAComparar[$CeroEncontrado])
                        {
                            $menor=$i;
                        }
                    }
                }
                else{
                    for($i=0;$i<count($listaAComparar);$i++)
                    {
                        if($listaIndices[$i]==$listaAComparar[6]){
                            $mayor=$i;
                        }elseif($listaIndices[$i]==$listaAComparar[$CeroEncontrado+1])
                        {
                            $menor=$i;
                        }
                    }
                }


                $listaServices = array();
                $listaServices[0] = "Consulta";
                $listaServices[1] = "Urgencia";
                $listaServices[2] = "Revision";
                $listaServices[3] = "Placa";
                $listaServices[4] = "Desparasitación";
                $listaServices[5] = "Eutanasia";
                $listaServices[6] = "Laboratorio";

                //$maxServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('finalizado','1')->min('servicio');
                //$minServices = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('finalizado','1')->max('servicio');
                $noServGenerales = Consulta::whereBetween('fecha',[new Carbon($fecha_inicial), new Carbon($fecha_final)])->where('medico_id',$request->input('medico_id'))->where('finalizado','1')->count('medico_id');

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
                    $promedioConsultas[] = Consulta::whereDate('fecha',$fechaPivote)->where('medico_id',$request->input('medico_id'))->where('servicio','LIKE','%Consulta%')->where('finalizado','1')->count('medico_id');   
                    $promedioServiciosGenerales[] = Consulta::whereDate('fecha',$fechaPivote)->where('medico_id',$request->input('medico_id'))->where('finalizado','1')->count('medico_id');
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
                $infoG4 = "Máximo servicio solicitado: ".$listaServices[$mayor];
                $infoG5 = "Mínimo servicio solicitado: ".$listaServices[$menor];
                $infoG6 = "Promedio de Consultas por día: ".round($promConPD);
                $infoG7 = "Promedio de Servicios atendidos por día: ".round($promServPD);
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
