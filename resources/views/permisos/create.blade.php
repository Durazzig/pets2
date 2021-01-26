@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nuevo Permiso') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('permisos.index') }}" class="btn btn-danger">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('permisos.store') }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Empleado') }}</label>
                            <input type="text" name="empleado" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Area') }}</label>
                            <select name="area" class="form-control @error('name') is-invalid @enderror">
                                <option value="Caja">Caja</option>
                                <option value="Recepcion">Recepción</option>
                                <option value="Hospital">Hospital</option>
                                <option value="Laboratorio">Laboratorio</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="Estetica">Estética</option>
                                <option value="Gerencia">Gerencia</option>
                                <option value="Quirofano">Quirófano</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha de permiso (Desde)') }}</label>
                            <input type="date" name="fecha_permiso" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha de permiso (Hasta)') }}</label>
                            <input type="date" name="fecha_permiso_final" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group form-row">
                    <div class="col-md-6">
                            <label for="phone">{{ __('Turno de permiso') }}</label>
                            <select name="turno" class="form-control @error('name') is-invalid @enderror">
                                <option value="Matutino">Matutino</option>
                                <option value="Vespertino">Vespertino</option>
                                <option value="Nocturno">Nocturno</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Cubre') }}</label>
                            <input type="text" name="sustituto" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Tipo de permiso') }}</label>
                            <select id="tipo_permiso" onchange="tipol();" name="tipo_permiso" class="form-control @error('name') is-invalid @enderror">
                                <option value="Permiso">Permiso</option>
                                <option value="Vacaciones">Vacaciones</option>
                                <option value="Incapacidad">Incapacidad</option>
                                <option value="Cambio">Cambio de turno</option>
                            </select>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Motivo') }}</label>
                            <textarea id="motivo" name="motivo" class="form-control @error('name') is-invalid @enderror">
                            </textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Anadir Permiso') }}</button>
                    </div>
                </form>
                @else
                    <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    function tipol()
    {
        var d=document.getElementById("tipo_permiso");
        var optionSelect= d.options[d.selectedIndex].text;
        if(optionSelect=="Vacaciones" || optionSelect=="Incapacidad")
        {
            document.getElementById("motivo").disabled=true;
        }
        else{
            document.getElementById("motivo").enabled=true;
        }
    }
</script>
@endsection

