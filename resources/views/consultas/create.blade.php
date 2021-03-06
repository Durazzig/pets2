@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ __('Nueva Consulta') }}</h3>
                    </div>
                    <div>
                        <a href="{{ route('consultas.index') }}" class="btn btn-danger font-weight-bold">
                            {{ __('Regresar')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(Auth::user())
                <form action="{{ route('consultas.store') }}" method="POST" autocomplete="on">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Medico') }}</label>
                            <select class="custom-select" name="medico_id" id="medico_id">
                                    @foreach($empleados as $empleado)
                                        <option value="{{$empleado->id}}">{{$empleado->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name">{{ __('Fecha') }}</label>
                            <input type="date" name="fecha" value="{{$fecha}}" id="medico" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Propietario') }}</label>
                            <input type="text" name="propietario" id="propietario" placeholder="Ingresa nombre de propietario" class="form-control">
                            <div id="lista_propietarios"></div> 
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Mascota') }}</label>
                            <input type="text" name="mascota" id="mascota" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Peso') }}</label>
                            <input type="text" name="peso" id="peso" placeholder="En Kg" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Edad') }}</label>
                            <input type="text" name="edad" id="edad" placeholder="En años" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Raza') }}</label>
                            <input type="text" name="raza" id="raza" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">{{ __('Servicios') }}</label>
                            <select class="custom-select" name="servicio">
                                <option value="Urgencia">Urgencia</option>
                                <option value="Consulta">Consulta</option>
                                <option value="Revision">Revision</option>
                                <option value="Desparasitacion">Desparasitación</option>
                                <option value="Placa">Placa</option>
                                <option value="Eutanasia">Eutanasia</option>
                                <option value="Laboratorio">Eutanasia</option>
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
    bootstrapValidate('#edad','numeric:Ingresa unicamente numeros')
    bootstrapValidate('#peso','numeric:Ingresa unicamente numeros')
    bootstrapValidate('#raza','alpha:Ingresa unicamente letras')
    bootstrapValidate('#propietario','regex:^[a-zA-Z ]*$:Ingresa unicamente letras')
    // $(document).ready(function () {
             
    //          $('#propietario').on('keyup',function() {
    //              var query = $(this).val(); 
    //              $.ajax({
                    
    //                  url:"{{ route('empleados.getEmpleados') }}",
               
    //                  type:"GET",
                    
    //                  data:{'country':query},
                    
    //                  success:function (data) {
                       
    //                      $('#lista_propietarios').html(data);
    //                  }
    //              })
    //              // end of ajax call
    //          });

             
    //          $(document).on('click', 'li', function(){
               
    //              var value = $(this).text();
    //              $('#propietario').val(value);
    //              $('#lista_propietarios').html("");
    //          });
    //      });
</script>
@endsection

