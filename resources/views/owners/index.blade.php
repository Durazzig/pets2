@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">Propietarios</h3>
                    </div>
                    <div>
                        <a href="{{ route('owners.create') }}" class="btn btn-primary">
                            {{ __('Nuevo Propietario')}}
                        </a>
                    </div>
                </div>
            </div>
            <div>
            <form action="{{ route('owners.filter') }}" method="POST">
            @csrf
                <div class="form-group form-row">
                    <div class="col-md-3">
                        <input type="text" name="name" id="name"  placeholder="Ingresa nombre de propietario" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button type="submit" name="action" value="filtrar" class="btn btn-success btn-md btn-block">{{ __('Buscar') }}</button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            @if(session('msg'))
                <div class="alert alert-warning" align="center">{{session('msg')}}</div>
            @endif
            @if(Auth::user())
            <table class="table table-hover table-responsive-lg table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Nombre') }}</th>
                        <th scope="col">{{ __('Direccion') }}</th>
                        <th scope="col">{{ __('Telefono') }}</th>
                        <th scope="col" style="width: 150px">{{ __('Mascotas') }}</th>
                        <th scope="col" style="width: 150px">{{ __('Editar') }}</th>
                        <th scope="col" style="width: 150px">{{ __('Eliminar') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($owners as $owner)
                    <tr>
                        <td>{{ $owner->id }}</td>
                        <td>{{ $owner->name }}</td>
                        <td>{{ $owner->address }}</td>
                        <td>{{ $owner->phone }}</td>
                        <td>
                            <a href="{{url('/owners/pets',$owner->id)}}" class="btn btn-outline-secondary btn-sm">
                                Mascotas
                            </a>
                        </td>
                        <td>
                            <a href="{{url('/owners/edit',$owner->id)}}" class="btn btn-outline-secondary btn-sm">
                                Editar
                            </a>
                        </td>
                        <td>
                            <form action="{{route('owners.delete',$owner->id)}}" method="POST">
								{{method_field('DELETE')}}
								@csrf
								<button type="submit" onclick="return confirm('¿Seguro que deseas eliminarlo?')" class="btn btn-danger btn-sm">Eliminar</button>
							</form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$owners->links()}}
            @endif
        </div>
    </div>
</div>
@endsection
