@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">Empleados</h3>
                    </div>
                    <div>
                        <a href="{{ route('empleados.create') }}" class="btn btn-success font-weight-bold">
                            {{ __('Nuevo Empleado')}}
                        </a>
                    </div>
                </div>
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
                         <th scope="col">{{ __('Usuario') }}</th>
                         <th scope="col">{{ __('Rol') }}</th>
                         <th scope="col" style="width: 100px">{{ __('Direccion') }}</th>
                         <th scope="col">{{ __('Celular') }}</th>
                         <th scope="col">{{ __('Area') }}</th>
                         <th scope="col">{{ __('Editar') }}</th>
                         <th scope="col">{{ __('Eliminar') }}</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($empleados as $empleado)
                     <tr>
                         <td>{{ $empleado->id }}</td>
                         <td>{{ $empleado->name }}</td>
                         <td>{{ $empleado->username }}</td>
                         <td>
                             @foreach($empleado->roles as $role)
                                 {{$role->name}}
                             @endforeach
                         </td>
                         <td>{{ $empleado->address }}</td>
                         <td>{{ $empleado->phone }}</td>
                         <td>{{ $empleado->work_area }}</td>
                         <td>
                             <a href="{{url('/empleados/edit',$empleado->id)}}" class="btn btn-outline-secondary btn-sm">
                                 Editar
                             </a>
                         </td>
                         <td>
                             <form action="{{route('empleados.delete',$empleado->id)}}" method="POST">
								{{method_field('DELETE')}}
								@csrf
								<button type="submit" onclick="return confirm('¿Seguro que deseas eliminarlo?')" class="btn btn-danger btn-sm">Eliminar</button>
							</form>
                         </td>
                     </tr>
                     @endforeach
                 </tbody>
             </table>
             {{$empleados->links()}}
             @endif
        </div>
    </div>
</div>
@endsection