@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Actualizar Proveedor') }}</h3>
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
                <form action="{{ route('providers.update', $providers->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" value="{{$providers->name}}" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Celular') }}</label>
                            <input type="text" name="phone" value="{{$providers->phone}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">{{ __('Actualizar') }}</button>
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

