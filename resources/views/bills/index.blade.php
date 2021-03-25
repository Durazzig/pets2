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
            <section class="card-header">
                <div class="row col-md-12">
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
                                <button type="submit" name="action" value="filtrar" class="btn btn-success btn-md btn-block">{{ __('Buscar') }}</button>
                            </div>
                            <div class="form-group col-md-4">
                                <button type="submit" name="action" value="imprimir" class="btn btn-success btn-md btn-block">{{ __('Exportar') }}</button>
                            </div>
                        </form>
                </div>
            </section>
            @if(session('msg'))
                <div class="alert alert-warning" align="center">{{session('msg')}}</div>
            @endif
            @if(Auth::user())
            <table class="table table-hover table-responsive-lg table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Proveedor') }}</th>
                        <th scope="col">{{ __('Folio') }}</th>
                        <th scope="col">{{ __('Fecha') }}</th>
                        <th scope="col">{{ __('Fecha de Entrega') }}</th>
                        <th scope="col">{{ __('Monto') }}</th>
                        <th scope="col">{{ __('Entrega') }}</th>
                        <th scope="col">{{ __('Editar') }}</th>
                        <th scope="col">{{ __('Eliminar') }}</th>
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
                        <td>{{ $bill->empleados->name }}</td>
                        <td>
                            <a href="{{url('/facturas/edit',$bill->id)}}" class="btn btn-outline-secondary btn-sm">
                                Editar
                            </a>
                        </td>
                        <td>
                            <form action="{{route('bills.destroy',$bill->id)}}" method="POST">
    							{{method_field('DELETE')}}
    							@csrf
    							<button type="submit" onclick="return confirm('¿Seguro que deseas eliminarlo?')" class="btn btn-danger btn-sm">Eliminar</button>
    						</form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$bills->links()}}
            @endif
        </div>
    </div>
</div>
@endsection