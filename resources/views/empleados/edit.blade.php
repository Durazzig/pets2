@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Actualizar Empleado') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('empleados.index') }}" class="btn btn-danger">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('empleados.update',$empleado->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="empleado_nombre" value="{{$empleado->name}}" id="nombre_empleado" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre de usuario') }}</label>
                            <input type="text" name="empleado_usuario" value="{{$empleado->username}}" id="empleado_username" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                    <div class="col-md-6">
                            <label for="name">{{ __('Area') }}</label>
                            <select name="empleado_area" class="form-control">
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
                        <div class="col-md-6">
                            <label for="name">{{ __('Rol') }}</label>
                            <select name="empleado_rol" class="form-control">
                                <option value="admin">Administrador</option>
                                <option value="cajero">Cajero</option>
                                <option value="mantenimiento">Mantinimiento</option>
                                <option value="medico_consulta">Medico de consulta</option>
                                <option value="apoyo_medico">Apoyo de hospital</option>
                                <option value="recepcionista">Recepcionista</option>
                                <option value="estetica">Encargado de estetica</option>
                                <option value="hostess">Hostess</option>
                                <option value="almacenista">Almacenista</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Direccion') }}</label>
                            <input type="text" value="{{$empleado->address}}" name="empleado_direccion" id="empleado_direccion" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Celular') }}</label>
                            <input type="text" value="{{$empleado->phone}}" name="empleado_celular" id="empleado_celular" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Actualizar') }}</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    bootstrapValidate('#nombre_empleado','regex:^[a-zA-Z ]*$:Ingresa unicamente letras')
    bootstrapValidate('#empleado_username','alphanum:Ingresa unicamente letras y numeros')
    bootstrapValidate('#empleado_direccion','alphanum:Ingresa unicamente letras y numeros')
    bootstrapValidate('#empleado_celular','numeric:Ingresa unicamente numeros')
</script>
@endsection

