@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">
                        <div>
                            <h3 class="mb-0">Facturas</h1>
                        </div>
                    </div>
                   
                        <div>
                            <a href="{{ route('bills.create') }}" class="btn btn-primary">
                                {{ __('Nueva Factura')}}
                            </a>
                        </div>
                    
                </div>
            </div>
            <div class="card-header">
                <div class="row">
                        <form action="{{ route('bills.filterDate') }}" method="POST" class="row" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-4">
                                <input type="date" name="desde" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="hasta" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <select class="custom-select" name="provider_id" id="provider_id">
                                    <option value="todos">Todos</option>
                                    @foreach($providers as $provider)
                                        <option value="{{$provider->id}}">{{$provider->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-success btn-md btn-block">{{ __('Buscar') }}</button>
                            </div>
                        </form>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <table class="table table-hover table-responsive-lg fixed-table-body">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Proveedor') }}</th>
                            <th scope="col">{{ __('Folio') }}</th>
                            <th scope="col">{{ __('Fecha') }}</th>
                            <th scope="col">{{ __('Fecha de Entrega') }}</th>
                            <th scope="col">{{ __('Monto') }}</th>
                            <th scope="col">{{ __('Entrega') }}</th>
                            <th scope="col">{{ __('Imagen') }}</th>
                            <th scope="col" style="width: 150px">{{ __('Opciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $bill)
                        <tr>
                            <td>{{ $bill->id }}</td>
                            <td>{{ $bill->provider->name }}</td>
                            <td>{{ $bill->folio }}</td>
                            <td>{{ $bill->fecha }}</td>
                            <td>{{ $bill->fecha_entrega }}</td>
                            <td>{{ $bill->monto }}</td>
                            <td>{{ $bill->empleado }}</td>
                            <td>
                                <img src="store_image/fetch_image{{$bill->id}}"width="75" />
                            </td>
                            <td>
                                <a href="{{url('/facturas/edit',$bill->id)}}" class="btn btn-outline-secondary btn-sm">
                                    Editar
                                </a>
                                <button class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $bill->id }}">Borrar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$bills->links()}}
                @else
                    <strong>Se ha detetectado que no te has logueado -> Por favor inicia sesion</strong>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection