@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nueva Mascota') }}</h3>
                    </div>
                    <div>
                        <a href="{{url('/owners/pets',$owner->id) }}" class="btn btn-danger">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('pets.storeFromOwner',$owner->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label>Nombre</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Especie</label>
                            <select class="custom-select" name="species" id="">
                                <option value="Canino">Canino</option>
                                <option value="Felino">Felino</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label>Raza</label>
                            <input name="raza" id="raza" class="form-control" required></input>
                        </div>
                        <div class="col-md-6">
                            <label>Fecha de nacimiento</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-12">
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
                @else
                    <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    bootstrapValidate('#raza','regex:^[a-zA-Z ]*$:Ingresa unicamente letras')
</script>
@endsection

