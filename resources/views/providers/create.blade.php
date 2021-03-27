@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nuevo Proveedor') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('providers.index') }}" class="btn btn-danger font-weight-bold">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('providers.store') }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Celular') }}</label>
                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" required>
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
    bootstrapValidate('#name','alpha:Ingresa unicamente caracteres alfabeticos')
    bootstrapValidate('#phone','numeric:Ingresa unicamente numeros')
</script>
@endsection

