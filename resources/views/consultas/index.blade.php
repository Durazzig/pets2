@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="mb-0">Consultas</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('consultas.create') }}" class="btn btn-success font-weight-bold">
                            {{ __('Nueva Consulta')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-header">
                    <div class="row">
                        <form action="{{ route('consultas.filterDate') }}" method="POST" class="row">
                            @csrf
                            <div class="col-md-4">
                                <input type="date" name="desde" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="hasta" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <select class="custom-select" name="medico_id" id="" required>
                                    <option value="todos">Todos</option>
                                    @foreach($medicos as $medico)
                                        <option value="{{$medico->id}}">{{$medico->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <button type="submit" name="action" value="filtrar" class="btn btn-success btn-md btn-block">{{ __('Buscar') }}</button>
                            </div>
                            <div class="form-group col-md-4">
                                <button type="submit" name="action" value="imprimir" class="btn btn-success btn-md btn-block">{{ __('Imprimir') }}</button>
                            </div>
                        </form>
                    </div>
            </div>
            @if(session('msg'))
                <div class="alert alert-warning" align="center">{{session('msg')}}</div>
            @endif
            @if(Auth::user())
            <table class="table table-hover table-responsive-lg table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Fecha') }}</th>
                        <th scope="col">{{ __('Medico') }}</th>
                        <th scope="col">{{ __('Hora Llegada') }}</th>
                        <th scope="col">{{ __('Hora Atencion') }}</th>
                        <th scope="col">{{ __('Hora Termino') }}</th>
                        <th scope="col">{{ __('Propietario') }}</th>
                        <th scope="col">{{ __('Mascota') }}</th>
                        <th scope="col">{{ __('Peso(Kg)') }}</th>
                        <th scope="col">{{ __('Edad') }}</th>
                        <th scope="col">{{ __('Raza') }}</th>
                        <th scope="col">{{ __('Descripcion Servicios') }}</th>
                        <th scope="col">{{ __('Editar') }}</th>
                        <th scope="col">{{ __('Eliminar') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($consultas as $consulta)
                    <tr>
                        <td>{{ $consulta->id }}</td>
                        <td>{{ $consulta->fecha}}</td>
                        <td>{{ $consulta->medico->name }}</td>
                        <td>{{ $consulta->hora_llegada }}</td>
                        <td>{{ $consulta->hora_atencion }}</td>
                        <td>{{ $consulta->hora_termino }}</td>
                        <td>{{ $consulta->propietario }}</td>
                        <td>{{ $consulta->mascota }}</td>
                        <td>{{ $consulta->peso }}</td>
                        <td>{{ $consulta->edad }}</td>
                        <td>{{ $consulta->raza }}</td>
                        <td>{{ $consulta->servicio }}</td>
                        <td>
                            <a href="{{route('consultas.edit',$consulta->id)}}" class="btn btn-outline-secondary btn-sm">
                                Editar
                            </a>
                        </td>
                        <td>
                            <form action="{{route('consultas.delete',$consulta->id)}}" method="POST">
								{{method_field('DELETE')}}
								@csrf
								<button type="submit" onclick="return confirm('¿Seguro que deseas eliminar esta consulta?')" class="btn btn-danger btn-sm">Eliminar</button>
							</form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$consultas->links()}}
            @else
                <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
            @endif
        </div>
    </div>
</div>
@endsection