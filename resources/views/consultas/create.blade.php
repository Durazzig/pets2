@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nueva Consulta') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('consultas.index') }}" class="btn btn-danger">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('consultas.store') }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Medico') }}</label>
                            <select class="custom-select" name="medico_id" id="medico_id">
                                    @foreach($empleados as $empleado)
                                        <option value="{{$empleado->id}}">{{$empleado->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha') }}</label>
                            <input type="date" name="fecha" value="{{$fecha}}" id="medico" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Propietario') }}</label>
                            <input type="text" name="propietario" id="propietario" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Mascota') }}</label>
                            <input type="text" name="mascota" id="mascota" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Peso') }}</label>
                            <input type="text" name="peso" id="peso" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Edad') }}</label>
                            <input type="text" name="edad" id="edad" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Raza') }}</label>
                            <input type="text" name="raza" id="raza" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Servicios') }}</label>
                            <input type="text" name="servicio" id="servicio" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Crear') }}</button>
                    </div>
                </form>
                @else
                    <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

