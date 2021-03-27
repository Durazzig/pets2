@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Actualizar Factura') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('bills.index') }}" class="btn btn-danger">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('bills.update',$bills->id) }}" method="POST">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Proveedor') }}</label>
                            <input type="text" name="provider_id" value="{{$bills->provider->name}}" id="phone" class="form-control @error('phone') is-invalid @enderror" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Folio') }}</label>
                            <input type="text" name="folio" id="folio" value="{{$bills->folio}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha') }}</label>
                            <input type="date" name="fecha" value="{{$bills->fecha}}" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Fecha de Entrega') }}</label>
                            <input type="date" name="fecha_entrega" value="{{$bills->fecha_entrega}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Monto') }}</label>
                            <input type="text" name="monto" id="monto" value="{{$bills->monto}}" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Empleado') }}</label>
                            <input type="text" name="empleado" value="{{$bills->empleados->name}}" id="phone" class="form-control @error('phone') is-invalid @enderror">
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
    bootstrapValidate('#folio','alphanum:Ingresa unicamente letras y numeros')
    bootstrapValidate('#monto','numeric:Ingresa unicamente numeros')
</script>
@endsection

