@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Editar Mascota') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('pets.updateFromOwner',$pet->id) }}" class="btn btn-danger">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('pets.updateFromOwner',$pet->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" value="{{$pet->name}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Especie') }}</label>
                            <select class="custom-select" name="species" id="">
                                <option value="{{$pet->species}}">{{$pet->species}}</option>
                                <option value="Canino">Canino</option>
                                <option value="Felino">Felino</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Raza') }}</label>
                            <input type="text" name="raze" value="{{$pet->raze}}" id="raza" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Fecha de nacimiento') }}</label>
                            <input type="date" name="dob" value="{{$pet->dob}}" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-12">
                            <select class="custom-select" name="status" id="">
                                <option value="{{$pet->status}}">{{$pet->status}}</option>
                                <option value="Hospitalizado">Hospitalizado</option>
                                <option value="Dado de alta">Dado de alta</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-12">
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
<script>

$( document ).ready(function() {

});
</script>
@endsection