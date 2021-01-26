@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Actualizar Permiso') }}</h3>
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
                <form action="{{ route('permisos.update',$permisos->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Empleado') }}</label>
                            <input type="text" name="empleado" id="name" value="{{$permisos->empleado}}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Area') }}</label>
                            <input name="area" value="{{$permisos->area}}" class="form-control @error('name') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha de permiso (Desde)') }}</label>
                            <input type="date" name="fecha_permiso" id="name" value="{{$permisos->fecha_permiso}}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha de permiso (Hasta)') }}</label>
                            <input type="date" name="fecha_permiso_final" value="{{$permisos->fecha_permiso_final}}"  id="name" class="form-control @error('name') is-invalid @enderror">
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
                                <input name="turno" value="{{$permisos->turno}}" class="form-control @error('name') is-invalid @enderror" {{$permisos->turno}}>
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="name">{{ __('Cubre') }}</label>
                                <input type="text" name="sustituto" id="name" value="{{$permisos->sustituto}}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Tipo de permiso') }}</label>
                                <input name="tipo_permiso" value="{{$permisos->tipo_permiso}}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>

                        <div class="col-md-6">
                        <label for="name">{{ __('Motivo') }}</label>
                            <input name="motivo" value="{{$permisos->motivo}}" class="form-control @error('name') is-invalid @enderror" disabled>
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
                @else
                    <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

