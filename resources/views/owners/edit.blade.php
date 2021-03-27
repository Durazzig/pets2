@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Editar Propietario') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('owners.index') }}" class="btn btn-danger font-weight-bold">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('owners.update',$owners->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" value="{{$owners->name}}" id="name" class="form-control @error('name') is-invalid @enderror" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Direccion') }}</label>
                            <input type="text" name="address" value="{{$owners->address}}" id="address" class="form-control @error('phone') is-invalid @enderror" required>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-12">
                            <label for="name">{{ __('Celular') }}</label>
                            <input type="tel" name="phone" id="phone" value="{{$owners->phone}}" class="form-control @error('name') is-invalid @enderror" pattern="{8}" required>
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
    bootstrapValidate('#phone','numeric:Ingresa unicamente numeros')
    bootstrapValidate('#name','regex:^[a-zA-Z ]*$:Ingresa unicamente letras')
</script>
@endsection
