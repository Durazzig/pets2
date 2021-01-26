@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Consulta') }}</h3>
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
                <form action="{{ route('consultas.update',$consulta->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Medico') }}</label>
                            <select class="custom-select" name="medico_id" id="">
                                @foreach($medicos as $medico)
                                    <option value="{{$medico->id}}">{{$medico->empleado}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Hora Llegada') }}</label>
                            <input type="text" name="hora_llegada" value="{{$consulta->hora_llegada}}" id="phone" class="form-control @error('phone') is-invalid @enderror" disabled>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Hora Atencion') }}</label>
                            <input type="text" name="hora_atencion" value="{{$consulta->hora_atencion}}" id="name" class="form-control @error('name') is-invalid @enderror" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Hora Termino') }}</label>
                            <input type="text" name="hora_termino" value="{{$consulta->hora_termino}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Propietario') }}</label>
                            <input type="text" name="propietario" value="{{$consulta->propietario}}" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Mascota') }}</label>
                            <input type="text" name="mascota" value="{{$consulta->mascota}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Peso') }}</label>
                            <input type="text" name="peso" value="{{$consulta->peso}}" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Edad') }}</label>
                            <input type="text" name="edad" value="{{$consulta->edad}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Raza') }}</label>
                            <input type="text" name="raza" value="{{$consulta->raza}}" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Servicios') }}</label>
                            <input type="text" name="servicio" value="{{$consulta->servicio}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-danger btn-lg btn-block">{{ __('Finalizar') }}</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Guardar') }}</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

