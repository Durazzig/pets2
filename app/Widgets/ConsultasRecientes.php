<?php

namespace App\Widgets;

use App\Consulta;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;

class ConsultasRecientes extends AbstractWidget
{
    public $reloadTimeout = 10;

    protected $config = [
        'count' => 5
    ];

    public function run()
    {
        $consultas = Consulta::whereDate('fecha', today())->where('finalizado',0)->paginate(5);
        return view('widgets.consultas_recientes', [
            'config' => $this->config,
            'consultas' => $consultas,
        ]);
    }
}
