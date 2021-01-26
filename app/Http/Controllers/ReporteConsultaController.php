<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Consulta;
use App\empleado;

class ReporteConsultaController extends Controller
{
    public function createWordDoc(Request $request){
    

        $fecha_inicial = $request -> input('desde');
        $fecha_final = $request -> input('hasta');
        $selectValue = $request -> input('medico_id');

        dd($fecha_final);
        $lista = array();
        $medicos = Consulta::distinct('medico_id')->get('medico_id');
        foreach($medicos as $medico){
            $noConsultas = Consulta::where('medico_id',$medico->medico_id)->count('medico_id');
            $medicosName = empleado::where('id',$medico->medico_id)->get('empleado');
            $maxServices = Consulta::where('medico_id',$medico->medico_id)->min('servicio');
            $minServices = Consulta::where('medico_id',$medico->medico_id)->max('servicio');
            //dd($maxServices);
            $lista[] = $noConsultas;
            $lista[] = $maxServices;
            $lista[] = $minServices;
            foreach($medicosName as $medicoName){
                $lista[] = $medicoName->empleado;
            }
        }
    dd($lista);
    }
}