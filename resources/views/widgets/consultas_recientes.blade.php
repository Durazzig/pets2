
<div class="card-body">
    <table class="table table-hover table-responsive-sm fixed-table-body">
        <thead class="bg-primary"> 
            <tr>
                <th>Medico</th>
                <th>Paciente</th>
                <th>Propietario</th>
                <th>Hora de llegada</th>
                <th>Servicio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultas as $consulta)
                <tr>
                    <td><h1>{{$consulta->medico->name}}</h1></td>
                    <td><h1>{{$consulta->mascota}}</h1></td>
                    <td><h1>{{$consulta->propietario}}</h1></td>
                    <td><h1>{{$consulta->hora_llegada}}</h1></td>
                    <td><h1>{{$consulta->servicio}}</h1></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
