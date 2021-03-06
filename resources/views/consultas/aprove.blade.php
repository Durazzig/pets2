@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Editar Consulta') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('consultas.index') }}" class="btn btn-danger font-weight-bold">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('consultas.update',$consulta->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Medico') }}</label>
                            <select class="custom-select" name="medico_id" id="">
                            <option value="{{$consulta->medico_id}}">{{$consulta->medico->name}}</option>
                                @foreach($medicos as $medico)
                                    <option value="{{$medico->id}}">{{$medico->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Propietario') }}</label>
                            <input type="text" name="propietario" value="{{$consulta->propietario}}" id="propietario" class="form-control @error('name') is-invalid @enderror">
                            <input type="text" name="hora_llegada" value="{{$consulta->hora_llegada}}" id="phone" class="form-control" hidden>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Mascota') }}</label>
                            <input type="text" name="hora_atencion" value="{{$consulta->hora_atencion}}" id="name" class="form-control" hidden>
                            <input type="text" name="mascota" value="{{$consulta->mascota}}" id="mascota" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Peso') }}</label>
                            <input type="text" name="peso" value="{{$consulta->peso}}" id="peso" class="form-control @error('name') is-invalid @enderror">
                            <input type="text" name="hora_termino" value="{{$consulta->hora_termino}}" id="phone" class="form-control" hidden>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="phone">{{ __('Edad') }}</label>
                            <input type="text" name="edad" value="{{$consulta->edad}}" id="edad" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Raza') }}</label>
                            <input type="text" name="raza" value="{{$consulta->raza}}" id="raza" class="form-control @error('name') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-12">
                            <label for="phone">{{ __('Servicios') }}</label>
                            <input type="text" name="servicio" value="{{$consulta->servicio}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <button type="submit" name="action" value="finalizar" class="btn btn-danger btn-lg btn-block">{{ __('Finalizar') }}</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="action" value="guardar" class="btn btn-success btn-lg btn-block">{{ __('Guardar') }}</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    bootstrapValidate('#edad','numeric:Ingresa unicamente numeros')
    bootstrapValidate('#peso','numeric:Ingresa unicamente numeros')
    bootstrapValidate('#raza','alpha:Ingresa unicamente letras')
    bootstrapValidate('#propietario','regex:^[a-zA-Z ]*$:Ingresa unicamente letras')
</script>
@endsection

