@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nueva Mascota') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('pets.index') }}" class="btn btn-danger font-weight-bold">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('pets.store') }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Propietario') }}</label>
                            <select class="custom-select" name="owner" id="">
                                <option>No tiene dueño</option>
                                @foreach($owners as $owner)
                                    <option value="{{$owner->id}}">{{$owner->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label>Especie</label>
                            <select class="custom-select" name="species" id="">
                                <option value="Canino">Canino</option>
                                <option value="Felino">Felino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Raza') }}</label>
                            <input type="text" name="raza" id="raza" class="form-control @error('name') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha de nacimiento') }}</label>
                            <input type="date" name="dob" id="dob" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label>Estatus</label>
                            <select class="custom-select" name="status" id="">
                                <option value="Hospitalizado">Hospitalizado</option>
                                <option value="Dado de alta">Dado de alta</option>
                                <option value="Fallecido">Fallecido</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Crear') }}</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    bootstrapValidate('#raza','regex:^[a-zA-Z ]*$:Ingresa unicamente letras')
</script>
@endsection

