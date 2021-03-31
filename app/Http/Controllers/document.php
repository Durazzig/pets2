<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permiso;
use App\User;
use Carbon\Carbon;


class document extends Controller
{
    //
    public function createWordDoc($id){
        $permisos = Permiso::find($id);
        $empleado = User::where('id',$permisos->empleado)->first();
        $sustituto = User::where('id',$permisos->sustituto)->first();

        $mytime = Carbon::now()->timezone('America/Mexico_City')->toDateString();
        $fechaT = Carbon::now()->toDateString();
        $fecha = Carbon::parse($fechaT);
        $date = $fecha->locale();
        //dd($fechaT);

        $fechaAux = Carbon::parse($permisos->fecha_permiso);
        $date1 = $fechaAux->locale();

        $fechaAux2 = Carbon::parse($permisos->fecha_permiso_final);
        $date2 = $fechaAux2->locale();

        $wordTest = new \PhpOffice\PhpWord\PhpWord();

        $newSection = $wordTest->addSection();

        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Arial');
        $fontStyle->setSize(22);

        $subtitule = new \PhpOffice\PhpWord\Style\Font();
        $subtitule->setBold(true);
        $subtitule->setName('Arial');
        $subtitule->setSize(14);

        $text =  new \PhpOffice\PhpWord\Style\Font();
        $text->setName('Arial');
        $text->setSize(11);

        $wordTest->setDefaultParagraphStyle(
            array(
                'align'      => 'both',
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                'spacing'    => 100,
                )
            );
        if($permisos->tipo_permiso == "Permiso" || $permisos->tipo_permiso == "Incapacidad")
        {
            $fech = "                                                                          Tuxtla Gutiérrez, Chis. ". $fecha->monthName . " " . $fecha->day . ", " . $fecha->year; 
            $salto = "";
            
            $part101 = "                   FORMATO PERMISO";
            $part102 = "                   FORMATO INCAPACIDAD";
            
            $part2 = "DR. JOE MICELI HERNANDEZ";
            $part3 = "GERENTE MÉDICO";
            
            $part41 = "Por este conducto solicito su autorización para realizar un cambio de turno en la siguiente fecha ". $fechaAux->day . " de " . $fechaAux->monthName . " del " . $fechaAux->year ;
            $part42 = "Por este conducto solicito su autorización para realizar un cambio de turno en las siguientes fechas: ". $fechaAux->day . " de " . $fechaAux->monthName . " del " . $fechaAux2->year . " al " . $fechaAux2->day . " de " . $fechaAux2->monthName . " del " . $fechaAux2->year;

            $part411 = "Por este conducto solicito su autorización para realizar un cambio de turno en la siguiente fecha ". $fechaAux->day . " de " . $fechaAux->monthName . " del " . $fechaAux->year ." debido a una incapacidad.";
            $part422 = "Por este conducto solicito su autorización para realizar un cambio de turno en las siguientes fechas: ". $fechaAux->day . " de " . $fechaAux->monthName . " del " . $fechaAux2->year . " al " . $fechaAux2->day . " de " . $fechaAux2->monthName . " del " . $fechaAux2->year . " debido a una incapacidad.";

            $part5 = "Empleado: " . $empleado->name . "                                         Área: " . $permisos->area . "                                 Turno: " . $permisos->turno ;
            $firma = "Firma: __________________________________";
            $part6 = "Se anexa justificante.";
            $part7 = "El compañero que realizará el cambio de turno conmigo es: ";
            $part8 = "Quien cubre: " . $sustituto->name;
            $part9 = "Sin más que agregar, agradezco de antemano, esperando contar con su aprobación.";
            $part10 = "                                                                                                                         Vo  Bo";
            $part11 = ".                                                                      ____________________________________";
            $part12 = "                                                                                                Lic. Ana Laura Mancilla Vega";
            $part13 = "                                                                                                                    Gerente General";

            //  $newSection->addImage('https://i.postimg.cc/JnkxkFTG/logopets-1.jpg');
            $newSection->addText($fech, $text);
            //$newSection->addText($salto, $text);

            if($permisos->tipo_permiso == "Permiso")
            {
                $newSection->addText($part101, $fontStyle);
            }else{
                $newSection->addText($part102, $fontStyle);
            }

            //$newSection->addText($salto, $text);
            $newSection->addText($part2, $subtitule);
            $newSection->addText($part3, $subtitule);
            //$newSection->addText($salto, $text);
            if((intval($fechaAux->day)-intval($fechaAux2->day))==0){
                if($permisos->tipo_permiso == "Permiso")
                {
                    $newSection->addText($part41, $text);
                }else{
                    $newSection->addText($part411, $text);
                }
            }
            else{
                if($permisos->tipo_permiso == "Permiso")
                {
                    $newSection->addText($part42, $text);
                }else
                {
                    $newSection->addText($part422, $text);
                }
            }
            //$newSection->addText($salto, $text);
            $newSection->addText($part5, $text);
            $newSection->addText($firma, $text);  
            $newSection->addText($part6, $text);
            $newSection->addText($part7, $text);
            $newSection->addText($part8, $text);
            $newSection->addText($firma, $text);
            $newSection->addText($part9, $text);
            $newSection->addText($salto, $text);
            $newSection->addText($part10, $text);
            $newSection->addText($part11, $text);
            $newSection->addText($part12, $text);
            $newSection->addText($part13, $text);


            $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
            try{
                $objectWriter->save(storage_path("Permiso" . $mytime .".docx"));
            }catch(Exception $e){
    
            }
            return response()->download(storage_path("Permiso" . $mytime . ".docx"));
        } else
        {
            $fech = "                                                                                     Tuxtla Gutiérrez, Chis. ". $fecha->monthName . " " . $fecha->day . ", " . $fecha->year; 
            $salto = "";
            
            $part101 = "                   FORMATO VACACIONES";
            $part102 = "         FORMATO CAMBIO DE TURNO";

            $part2 = "DR. JOE MICELI HERNANDEZ";
            $part3 = "GERENTE MÉDICO";
           
            $part41 = "Por este conducto solicito su autorización para faltar a mis labores al hacer uso de mis vacaciones en las siguientes fechas: ". $fechaAux->day . " de " . $fechaAux->monthName . " del " . $fechaAux2->year . " al " . $fechaAux2->day . " de " . $fechaAux2->monthName . " del " . $fechaAux2->year;
            $part42 = "Por este conducto solicito su autorización para realizar un cambio de turno en las siguientes fechas: ". $fechaAux->day . " de " . $fechaAux->monthName . " del " . $fechaAux2->year . " al " . $fechaAux2->day . " de " . $fechaAux2->monthName . " del " . $fechaAux2->year;
           
            $part5 = "Empleado: " . $empleado->name . "                                         Área: " . $permisos->area . "                                 Turno: " . $permisos->turno ;
            $firma = "Firma: __________________________________";
            $part6 = "Se anexa justificante.";
            $part7 = "El compañero que realizará el cambio de turno conmigo es: ";
            $part8 = "Quien cubre: " . $sustituto->name;
            $part9 = "Sin más que agregar, agradezco de antemano, esperando contar con su aprobación.";
            $part10 = "                                                                                                                         Vo  Bo";
            $part11 = ".                                                                      ____________________________________";
            $part12 = "                                                                                                Lic. Ana Laura Mancilla Vega";
            $part13 = "                                                                                                                    Gerente General";

            $newSection->addImage('https://i.postimg.cc/JnkxkFTG/logopets-1.jpg');
            $newSection->addText($fech, $text);
            $newSection->addText($salto, $text);

            if($permisos->tipo_permiso == "Vacaciones")
            {
                $newSection->addText($part101, $fontStyle);
            }else{
                $newSection->addText($part102, $fontStyle);
            }

            $newSection->addText($salto, $text);
            $newSection->addText($part2, $subtitule);
            $newSection->addText($part3, $subtitule);
            $newSection->addText($salto, $text);

            if($permisos->tipo_permiso == "Vacaciones")
            {
                $newSection->addText($part41, $text);
            }else{
                $newSection->addText($part42, $text);
            }
           
            $newSection->addText($salto, $text);
            $newSection->addText($part5, $text);
            $newSection->addText($firma, $text);  
            $newSection->addText($part6, $text);
            $newSection->addText($part7, $text);
            $newSection->addText($part8, $text);
            $newSection->addText($firma, $text);
            $newSection->addText($part9, $text);
            $newSection->addText($salto, $text);
            $newSection->addText($part10, $text);
            $newSection->addText($part11, $text);
            $newSection->addText($part12, $text);
            $newSection->addText($part13, $text);

            $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, "Word2007");
            try{
                $objectWriter->save(storage_path("Permiso".$mytime.".docx"));
            }catch(Exception $e){
    
            }
            return response()->download(storage_path("Permiso".$mytime.".docx"));
        }
    }
}