@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Actualizar Permiso') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('permisos.index') }}" class="btn btn-danger font-weight-bold">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('permisos.update',$permiso->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Empleado') }}</label>
                            <select class="custom-select" name="empleado">
                                @foreach($empleados as $empleado)
                                    <option value="{{$empleado->id}}">{{$empleado->name}}</option>
                                @endforeach
                            </select>
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
                            <input type="date" name="fecha_permiso" id="name" value="{{$permiso->fecha_permiso}}" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha de permiso (Hasta)') }}</label>
                            <input type="date" name="fecha_permiso_final" value="{{$permiso->fecha_permiso_final}}"  id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                                <label for="phone">{{ __('Turno de permiso') }}</label>
                                <select name="turno" class="form-control @error('name') is-invalid @enderror">
                                    <option value="{{$permiso->turno}}">{{$permiso->turno}}</option>
                                    <option value="Matutino">Matutino</option>
                                    <option value="Vespertino">Vespertino</option>
                                    <option value="Nocturno">Nocturno</option>
                                    <option value="Mixto">Mixto</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="name">{{ __('Cubre') }}</label>
                                <select class="custom-select" name="sustituto">
                                    <option value="{{$permiso->sustituto}}">{{$permiso->sustitutos->name}}</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{$empleado->id}}">{{$empleado->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Tipo de permiso') }}</label>
                                <input name="tipo_permiso" value="{{$permiso->tipo_permiso}}" class="form-control @error('name') is-invalid @enderror">
                        </div>

                        <div class="col-md-6">
                        <label for="name">{{ __('Motivo') }}</label>
                            <input name="motivo" value="{{$permiso->motivo}}" class="form-control @error('name') is-invalid @enderror" disabled>
                        </div>
                    </div>
                    <div class="form-group form-row">
                            <label for="name">{{ __('Aprobado') }}</label>
                            <select name="aprobado" class="form-control @error('name') is-invalid @enderror">
                                <option value="Checked">Aprobado</option>
                                <option value="NoChecked">Negado</option>
                                </select>
                    
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Actualizar') }}</button>
                    </div>   
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

