@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">
                        <div>
                            <h3 class="mb-0">Consultas</h3>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <a href="{{ route('consultas.create') }}" class="btn btn-primary">
                                {{ __('Nueva Consulta')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header">
                    <div class="row">
                        <form action="{{ route('consultas.filterDate') }}" method="POST" class="row">
                            @csrf
                            <div class="col-md-4">
                                <input type="date" name="desde" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="hasta" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <select class="custom-select" name="medico_id" id="">
                                    <option value="todos">Todos</option>
                                    @foreach($medicos as $medico)
                                        <option value="{{$medico->id}}">{{$medico->empleado}}</option>
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
            <div class="card-body">
                @if(Auth::user())
                <table class="table table-hover table-responsive-xl fixed-table-body">
                    <thead>
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
</div>
@endsection