@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nueva Factura') }}</h3>
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
                <form action="{{ route('bills.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Proveedor') }}</label>
                            <select class="custom-select" name="provider_id" id="provider_id">
                                @foreach($providers as $provider)
                                    <option value="{{$provider->id}}">{{$provider->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Folio') }}</label>
                            <input type="text" name="folio" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha') }}</label>
                            <input type="date" name="fecha" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Fecha de Entrega') }}</label>
                            <input type="date" name="fecha_entrega" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Monto') }}</label>
                            <input type="text" name="monto" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Empleado') }}</label>
                            <input type="text" name="empleado" id="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="phone">{{ __('Imagen de factura') }}</label>
                            <input type="file" name="user_image"/>
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
@endsection

