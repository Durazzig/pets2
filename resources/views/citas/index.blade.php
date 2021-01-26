@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">Citas</h3>
                    </div>
                    <div>
                        <a href="{{ route('citas.create') }}" class="btn btn-primary">
                            {{ __('Nueva Cita')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <table class="table table-hover table-responsive-lg fixed-table-body">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Paciente') }}</th>
                            <th scope="col">{{ __('Propietario') }}</th>
                            <th scope="col">{{ __('Hora') }}</th>
                            <th scope="col">{{ __('Fecha') }}</th>
                            <th scope="col">{{ __('Medico') }}</th>
                            <th scope="col" style="width: 150px">{{ __('Opciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $cita)
                        <tr>
                            <td>{{ $cita->id }}</td>
                            <td>{{ $cita->name }}</td>
                            <td>{{ $cita->phone }}</td>
                            <td>
                                <a href="{{url('/providers/edit',$provider->id)}}" class="btn btn-outline-secondary btn-sm">
                                    Editar
                                </a>
                                <button class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $provider->id }}">Borrar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection