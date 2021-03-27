<?php

namespace App\Widgets;

use App\Consulta;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;

class ConsultasRecientes extends AbstractWidget
{
    public $reloadTimeout = 5;

    protected $config = [
        'count' => 5
    ];

    public function run()
    {
        $fecha = Carbon::now()->timezone('America/Mexico_City')->toDateString();
        $consultas = Consulta::whereDate('fecha', $fecha)->where('finalizado',0)->paginate(5);
        return view('widgets.consultas_recientes', [
            'config' => $this->config,
            'consultas' => $consultas,
        ]);
    }
}
